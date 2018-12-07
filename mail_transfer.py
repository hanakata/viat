# -*- coding: utf-8 -*-
import smtplib
import configparser
import datetime
from email.mime.text import MIMEText
import mysql.connector
import requests
from bs4 import BeautifulSoup

user_agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36'
headers = {'User-Agent': user_agent}
connecter = mysql.connector.connect(
    user='root', password='ppppp0!!', host='localhost', database='kb_checker')
now_date = datetime.datetime.now()
d_date = now_date
d = d_date.strftime("%Y-%m")
year = d_date.strftime("%Y")
month = d_date.strftime("%m")
cur = connecter.cursor()
inifile = configparser.ConfigParser()
inifile.read('./config.ini', encoding='utf-8')

smtp_server = inifile.get('mail', 'smtp_server')
fromaddr = inifile.get('mail', 'smtp_from')
subject = inifile.get('mail', 'subject')
cur.execute("select company_code,user_mail_to,user_mail_cc,user_mail_bcc,company_name from user_mail_setting inner join company_info on user_mail_setting.company_code = company_info.code where user_mail_to != ''")
for mail in cur.fetchall():
    system = ""
    jvn_msg = ""
    company_code = mail[0]
    toaddr = mail[1]
    ccaddr = mail[2]
    bccaddr = mail[3]
    customer = mail[4]
    raw_msg = customer + "\nご担当者様"
    cur.execute(
        "select * from user_sent_setting where company_code = '" + company_code + "'")
    for sent in cur.fetchall():
        severity_flg = 0
        severity_title_flg = 0
        severity_count = 0
        vendor = sent[1]
        product = sent[2]
        cve_id_list = []
        cur.execute("select DISTINCT cve_id from kb_checker.nvd_jsons join kb_checker.descriptions on kb_checker.nvd_jsons.id = kb_checker.descriptions.nvd_json_id where last_modified_date LIKE '" +
                    d + "%' and value like '%" + product + "%'")
        for cve_id in cur.fetchall():
            cve_id_list.append(cve_id[0])
        cur.execute("select cve_id from jvns where last_modified_date LIKE '" +
                    d + "%' and summary like '%" + product + "%'")
        for jvn_cve_id in cur.fetchall():
            if jvn_cve_id[0] in cve_id_list:
                cve_id_list.append(jvn_cve_id[0])
        for cve_id in cve_id_list:
            cur.execute(
                "select title,jvn_link from jvns where cve_id = '" + cve_id + "'")
            for jvn_info in cur.fetchall():
                alert_url = jvn_info[1]
                target_html = requests.get(alert_url, headers=headers)
                html = BeautifulSoup(target_html.text, "lxml")
                line = html.find('div', class_='float_left')
                rank = line.find('a')
                if product == "Garoon":
                    if 5.0 < float(rank.text):
                        severity_flg = 1
                        if severity_title_flg == 0:
                            jvn_msg += "\n★ " + product + " の重大度 Medium以上の脆弱性は以下のとおりです。"
                            severity_title_flg = 1
                        jvn_msg += "\n　CVE番号: " + cve_id + "\n"
                        if 7.0 < float(rank.text):
                            jvn_msg += "　重大度: High(スコア：" + rank.text + ")\n"
                        elif 5.0 < float(rank.text):
                            jvn_msg += "　重大度: Medium(スコア：" + rank.text + ")\n"
                        jvn_msg += "　内容: " + jvn_info[0] + "\n"
                        jvn_msg += "　詳細情報URL: " + jvn_info[1] + "\n"
                        severity_count += 1
                else:
                    if 7.0 < float(rank.text):
                        severity_flg = 1
                        if severity_title_flg == 0:
                            jvn_msg += "\n★ " + product + " の重大度 High以上の脆弱性は以下のとおりです。"
                            severity_title_flg = 1
                        jvn_msg += "\n　CVE番号: " + cve_id + "\n"
                        jvn_msg += "　重大度: High(スコア：" + rank.text + ")\n"
                        jvn_msg += "　内容: " + jvn_info[0] + "\n"
                        jvn_msg += "　詳細情報URL: " + jvn_info[1] + "\n"
                        severity_count += 1
                        if product.find('Windows Server') != -1:
                            kb_flg = 0
                            jvn_msg += "　対応するKB番号は以下の通りです。\n ※環境(バージョン及びインストールアプリケーションなど）によっては適用する必要のないKBもございます。\n"
                            cur.execute("select DISTINCT  kb_number from kb_list inner join production_list on kb_list.producrion_id = production_list.production_id where cve_number = '" +
                                        cve_id + "' and production_name like '%" + product + "%'")
                            for kb in cur.fetchall():
                                jvn_msg += "　　" + kb[0] + "\n"
                                kb_flg = 1
                        if kb_flg == 0:
                            jvn_msg += "　　対応するKBはまだ公開されておりませんでした。\n"
        if severity_flg == 0:
            jvn_msg += "\n★ " + product + " の配信対象脆弱性情報はありませんでした。"
        system += "　" + vendor + " : " + product + \
            " / " + str(severity_count) + "件\n"

    raw_msg += "\n\nご契約いただいているシステムに関する脆弱性情報についてお知らせです。"
    raw_msg += "\n本メールは今月更新された脆弱性情報をお送りしております。"
    raw_msg += "\n\n■ご契約いただいているシステムおよび脆弱性件数:\n" + system + "\n"

    raw_msg += jvn_msg

    raw_msg += "\n\n※本メールにて配信しております脆弱性情報につきましてGaroonは重大度Medium以上、\n"
    raw_msg += "  その他の製品は重大度High以上のみとなっております。\n"
    raw_msg += "  また本メールは送信専用アドレスより送信しております。\n"
    raw_msg += "  お問合せは担当営業までお願い致します。"

    jp = 'iso-2022-jp'
    msg = MIMEText(raw_msg.encode(jp), 'plain', jp,)
    msg['Subject'] = subject + " " + year + "年 " + month + "月"
    msg['From'] = fromaddr
    msg['To'] = toaddr
    msg['Cc'] = ccaddr
    msg['Bcc'] = bccaddr
    server = smtplib.SMTP(smtp_server)
    try:
        server.ehlo()
        server.has_extn('STARTTLS')
        server.starttls()
        server.ehlo()
        server.login('softcreate001', 'FPFcg81AbYRxwRPQ')
        server.send_message(msg)
        print("Successfully sent email")
    except Exception:
        print("Error: unable to send email")
        import traceback
        traceback.print_exc()
