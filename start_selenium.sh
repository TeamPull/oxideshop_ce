#!/usr/bin/env bash
set -e
xfce4-session&
java -jar selenium-server-standalone-2.47.1.jar &>/dev/null&
vendor/bin/runtests-selenium
