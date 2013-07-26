#!/bin/bash
## Usage: pgsql-backup-all.sh [options] dst_path
##
## Backpu all databases
##
## Options:
##   -h, --help    Display this message.
##   -v            Display version
##

VERSION=0.1.0

usage() {
  [ "$*" ] && echo "$0: $*"
  sed -n '/^##/,/^$/s/^## \{0,1\}//p' "$0"
  exit 2
} 2>/dev/null

#region Seting params _________________________________________________________
if [ $# == 0 ] ; then
    usage "unknown option"
fi

while getopts "vh" optname
  do
    case "$optname" in
      "v")
        echo "Version $VERSION"
        exit 0;
        ;;
      "h")
        usage "help"
        exit 0;
        ;;
      "?")
        usage "unknown option"
        ;;
      ":")
        echo "No argument value for option $OPTARG"
        exit 0;
        ;;
      *)
        echo "Unknown error while processing options"
        exit 0;
        ;;
    esac
  done

shift $(($OPTIND - 1))

if [ -z "$1" ] ; then
    usage "unknown option"
fi

dst=$1

#endregion ____________________________________________________________________

#region Main __________________________________________________________________

for i in `su - postgres -c "psql -A -t -c\"SELECT datname FROM pg_database WHERE datistemplate = false\""`
do
	su - postgres -c "/usr/bin/pg_dump -U postgres $i | gzip > $dst/$i-`date +%Y-%m-%d`.sql.gz"
done

#endregion ____________________________________________________________________
