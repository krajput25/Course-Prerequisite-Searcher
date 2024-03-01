SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
SQL_DIR=$SCRIPT_DIR"/reset_db.sql"
mysql -u db_user -pdb_pass < $SQL_DIR
echo "Database successfully reset to default values"