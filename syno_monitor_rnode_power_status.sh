#!/bin/bash
ADMIN_VIP=$1
ADMIN_PASS=$2
/usr/bin/php /usr/lib/zabbix/externalscripts/Synology/monitorRnodePowerStatus.php $ADMIN_VIP $ADMIN_PASS | grep -c "status_normal"
