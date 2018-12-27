#!/usr/bin/env bash
set -e
java -jar selenium-server-standalone-2.47.1.jar&
vendor/bin/runtests-selenium
