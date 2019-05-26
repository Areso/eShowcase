#!/usr/bin/env python3
#This project licensed under MIT License
#author:  Gladyshev Anton 
#contact: Returnstrike [at] ya.ru
import argparse
import sys
from robobrowser import RoboBrowser
from time import sleep

parser = argparse.ArgumentParser()
parser.add_argument("u", help="filename without .csv extension", type=str)
args = parser.parse_args()
filename = args.u

fconf = open('config.txt', 'r')
tconf = fconf.read()
fconf.close()
conf_list = tconf.split('\n')

username = conf_list[0]
password = conf_list[1]
hostname = conf_list[2]
dbport   = conf_list[3]
database = conf_list[4]


filename = filename + '.csv'
ftab = open(filename, 'r')
tables = ftab.read()
ftab.close()
tables_lines = tables.split('fFx')
line_count = len(tables_lines)-1

