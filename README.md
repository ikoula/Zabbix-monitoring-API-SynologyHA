# Zabbix-monitoring-API-SynologyHA

External scripts Zabbix for (DSM) Synology HA monitoring 
==========================================================================

L'objectif de ces scripts est de monitorer le statut d'un Synology passive server et de ses disques dans un context de 2 Synology configurés en HA (Active/Passive). 

Dans un context Synology HA, il n'est en effet plus possible d'accéder au passive server (le Synology slave) via une autre méthode (ex: SSH, SNMP) que par l'API du Synology Master (active server) et donc d'en monitorer l'état global et de ses disques (important dans le cas il deviendrait l'active server/le Synology Master en cas de bascule).

Les scripts syno_monitor_rdisk_count.sh; syno_monitor_rdisk_status_normal.sh; syno_monitor_rnode_power_status.sh; syno_monitor_rnode_status.sh sont les scripts exécutés via les items Zabbix external checks par le zabbix_proxy ou zabbix_server monitorant le NAS Synology HA.

REMARQUE: Il est important de monitorer le reste des éléments de vos Synology par d'autres méthodes (ex: https://share.zabbix.com/storage-devices/synology/synology-dsm-5), ces scripts ne monitorant QUE ce qu'il ne l'est pas autrement que par l'API (ni SSH, ni SNMP, etc.), ils viennent donc compléter un monitoring existant.

Pour utiliser ces scripts en context monitoring Zabbix vous devez :
-----------------------------------------------------------

- copier/cloner l'ensemble des fichiers dans le répertoire "/usr/lib/zabbix/externalscrits/" des Zabbix Proxy (ou votre Zabbix Server) qui monitorent vos Synology HA avec les permissions qui vont bien (droits d'exécution et owner/group zabbix).
- Avoir configuré le paramètre "ExternalScripts=" de la configuration de vos Zabbix Proxy/Server avec le chemin du répertoire ci-dessus
- Créer des items external checks faisant appel aux scripts .sh avec comme premier paramètre la VIP du cluster Synology HA et comme second paramètre le mot de passe de l'utilisateur admin DSM Synology.
- Importer le template Zabbix zbx_synologyHA_passive-server.xml.
- Paramétrer les macros Zabbix {$ADMIN_VIP} et {$ADMIN_PASS} (utilisés par les external scripts pour se connecter à l'API DSM Synology) sur l'hôte Synology sur lequel vous avez ajouté ce template.
