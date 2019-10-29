#!/bin/bash

cd /root/klingogbang/
docker stop kob_web > /dev/null 2>&1 &
docker-compose up -d web > /dev/null 2>&1 &

exit 0
