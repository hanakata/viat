# -*- coding: utf-8 -*-
import smtplib
import configparser
import datetime
from email.mime.text import MIMEText
import mysql.connector

connecter = mysql.connector.connect(user='root', password='ppppp0!!', host='localhost', database='kb_checker')
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
    severity_flg = 0
    system = ""
    jvn_msg = ""
    company_code = mail[0]
    toaddr = mail[1]
    ccaddr = mail[2]
    bccaddr = mail[3]
    customer = mail[4]
    raw_msg = customer + "\nご担当者様"
    cur.execute("select * from user_sent_setting where company_code = '" + company_code + "'")
    for sent in cur.fetchall():
        severity_high_check = 0
        vendor = sent[1]
        product = sent[2]
        system += "　" + vendor + " : " + product + "\n"
        cve_detail_id_list = []
        cur.execute("select DISTINCT cve_detail_id from nvds where last_modified_date LIKE '" + d + "%' and summary like '%" + product + "%'")
        severity_high_check = 0
        for cve_detail_id in cur.fetchall():
            cve_detail_id_list.append(cve_detail_id[0])
        for cve_detail_id in cve_detail_id_list:
            severity_flg = 1
            cur.execute("select cve_id,title,severity,jvn_link from jvns where cve_detail_id = '" + str(cve_detail_id) + "' and last_modified_date like '" + d + "%'")
            for cve_detail_id in cur.fetchall():
                if product == "Garoon":
                    if cve_detail_id[2] == "High" or cve_detail_id[2] == "Medium":
                        if severity_high_check == 0:
                            jvn_msg += "\n★ " + product + " の重大度 Medium以上の脆弱性は以下のとおりです。"
                        severity_high_check = 1
                        cve_id = cve_detail_id[0]
                        title = cve_detail_id[1]
                        severity = cve_detail_id[2]
                        jvn_link = cve_detail_id[3]
                        jvn_msg += "\n　CVE番号: " + cve_id + "\n"
                        jvn_msg += "　重大度: " + severity + "\n"
                        jvn_msg += "　内容: " + title + "\n"
                        jvn_msg += "　詳細情報URL: " + jvn_link + "\n"
                else:
                    if cve_detail_id[2] == "High" and cve_detail_id[1].find('Android') == -1:
                        if severity_high_check == 0:
                            jvn_msg += "\n★ " + product + " の重大度 High以上の脆弱性は以下のとおりです。"
                        severity_high_check = 1
                        cve_id = cve_detail_id[0]
                        title = cve_detail_id[1]
                        severity = cve_detail_id[2]
                        jvn_link = cve_detail_id[3]
                        jvn_msg += "\n　CVE番号: " + cve_id + "\n"
                        jvn_msg += "　重大度: " + severity + "\n"
                        jvn_msg += "　内容: " + title + "\n"
                        jvn_msg += "　詳細情報URL: " + jvn_link + "\n"
                        if product.find('Windows Server') != -1:
                            kb_flg = 0
                            jvn_msg += "　対応するKB番号は以下の通りです。\n ※環境(バージョン及びインストールアプリケーションなど）によっては適用する必要のないKBもございます。\n"
                            cur.execute("select DISTINCT  kb_number from kb_list inner join production_list on kb_list.producrion_id = production_list.production_id where cve_number = '" + cve_id + "' and production_name like '%" + product + "%'")
                            for kb in cur.fetchall():
                                jvn_msg += "　　" + kb[0] + "\n"
                                kb_flg = 1
                        if kb_flg == 0:
                            jvn_msg += "　　対応するKBはまだ公開されておりませんでした。\n"
        if severity_high_check == 0:
            jvn_msg += "★ " + product + " のHigh以上の脆弱性情報はありませんでした。\n\n"

    raw_msg += "\n\nご契約いただいているシステムに関する脆弱性情報についてお知らせです。"
    raw_msg += "\n\n■ご契約いただいているシステム:\n" + system
    
    if severity_flg == 0:
        raw_msg += "ご契約いただいているシステムに関する脆弱性情報はありませんでした。\n\n"
    else:
        raw_msg += jvn_msg
    raw_msg += "\n\n※本メールにて配信しております脆弱性情報につきましてGaroonは重大度Medium以上、\n"
    raw_msg += "  その他の製品は重大度High以上のみとなっております。\n"
    raw_msg += "  また本メールは送信専用アドレスより送信しております。\n"
    raw_msg += "  お問合せは担当営業までお願い致します。"
    
    jp='iso-2022-jp'
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