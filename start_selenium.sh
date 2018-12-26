#!/usr/bin/env sh
xfce4-session&
java -jar /opt/selenium/selenium.jar&
x11vnc -forever
