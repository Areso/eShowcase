#!/usr/bin/python3
# This script licensed under MIT License
# This very version made by Anton Gladyshev (Areso)
import sys
import os
import requests
import time
import mysql.connector


def send_message(msg_params):
	sms_url = 'https://sms.ru/sms/send?api_id=key&to=number&msg=message&json=1'
	sms_url = sms_url.replace('number', msg_params[5])
	sms_url = sms_url.replace('key', msg_params[6])
	message = 'check new orders in discont shop!'
	print(message)
	sms_url = sms_url.replace('message', message)
	# TODO try block. Sometimes SMS.RU get stuck
	sms_response = requests.get(sms_url)
	print('sms sent')


def myloading():
	cfgpath = "/var/config.txt"
	fconf = open(cfgpath, 'r')
	tconf = fconf.read()
	fconf.close()
	conf_list = tconf.split('\n')
	
	username = conf_list[0]
	password = conf_list[1]
	hostname = conf_list[2]
	dbport   = conf_list[3]
	database = conf_list[4]
	phone    = conf_list[5]
	apikey   = conf_list[6]
	
	#if len(myassets) > 0:
	#    for each_asset in myassets:
	#        sms_counter_dict[each_asset] = 0
	
	#myparams = []
	#myparams.append(mykey)
	#myparams.append(mynumber)
	return conf_list


def main_loop(loop_params):
	while True:
		mydb = mysql.connector.connect(
			host=loop_params[2],
			user=loop_params[0],
			passwd=loop_params[1],
			database=loop_params[4]
		)
		mycursor = mydb.cursor()
		sql = "select * from orders where is_ack = 0"
		print(sql)
		mycursor.execute(sql)
		myresult = mycursor.fetchall()
		nonsentorders = len(myresult)
		if nonsentorders > 0:
			sql = "UPDATE orders SET is_ack = 1 WHERE is_ack = 0"
			mycursor.execute(sql)
			mydb.commit()
			send_message(loop_params)
		time.sleep(15)


if __name__ == "__main__":
	my_params = myloading()
	main_loop(my_params)
else:
	print("the program is being imported into another module")
