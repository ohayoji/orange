ACCOUNT_NAME=blt37489
HOME_DIR=/home/turba
BACKUP_DIR=${HOME_DIR}/db_bk
BACKUP_FILEPATH=${BACKUP_DIR}/turba_orange-backup.sql.gz
DB_ADDRESS=mysql635.db.sakura.ne.jp
/usr/local/bin/mysqldump --opt -c --host=${DB_ADDRESS} \
 --user=turba --password=komazawa_test01 \
 --database turba_orange | /usr/bin/gzip > ${BACKUP_FILEPATH}
