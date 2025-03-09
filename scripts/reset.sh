#!/bin/bash
export PATH=$PATH:/usr/local/bin

processName=$1

pm2 restart $processName --watch
