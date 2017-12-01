# -*- coding: utf-8 -*-
import csv
import pyodbc
import smtplib
import configparser
import re
import logging
import datetime
from email.mime.text import MIMEText

d = datetime.datetime.today()

inifile = configparser.SafeConfigParser()
inifile.read('./config.ini')

smtp_server = inifile.get('mail', 'smtp_server')
fromaddr = inifile.get('mail', 'smtp_from')
toaddr = inifile.get('mail', 'smtp_to')
ccaddr = inifile.get('mail', 'smtp_cc')
bccaddr = inifile.get('mail', 'smtp_bcc')

subject = inifile.get('mail', 'subject')

default_threshold  = inifile.get('system', 'default_threshold')
csv_file = inifile.get('system', 'csv')

log_file = inifile.get('system', 'log')
log_date = d.strftime("%Y%m%d")
logging.basicConfig(filename=log_file+"_"+log_date+".log",level=logging.DEBUG)

raw_msg = "データベース容量のチェック結果です。"

pattern = r"(.*)\d{8,}$"
repatter = re.compile(pattern)

jp='iso-2022-jp'
raw_msg += u'\n\n'+u'問題ありませんでした。'
msg = MIMEText(raw_msg.encode(jp), 'plain', jp,)

msg['Subject'] = subject
msg['From'] = fromaddr
msg['To'] = toaddr
msg['Cc'] = ccaddr
msg['Bcc'] = bccaddr
try:
    server = smtplib.SMTP(smtp_server)
    server.send_message(msg)
    print("Successfully sent email")
except Exception:
    print("Error: unable to send email")