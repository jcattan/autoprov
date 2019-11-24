#!/usr/bin/expect



set host [lindex $argv 0]
set login [lindex $argv 1]
set password [lindex $argv 2]
set password2 [lindex $argv 3]
set model [lindex $argv 4]
set server [lindex $argv 5]

spawn ssh $host -l $login
expect "Are you sure you want to continue connecting" {send -- "yes\n"}
expect "password:"
send -- "$password\n"
expect "password:" {send -- "$password2\n"}
expect "$model"
send -- "config\n"
expect "CONFIG >"
send -- "set 237 $server\n"
expect "CONFIG >"
send -- "set 212 0\n"
expect "CONFIG >"
send -- "set 145 0\n"
expect "CONFIG >"
send -- "commit\n"
expect "CONFIG >"
send -- "exit\n"
expect "$model"
send -- "reboot\n"
expect "$model"
send -- "yes\n"
expect "$model"
