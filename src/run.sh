#! /bin/bash
#
# run.sh
#   A simple script to init/update your local copy of the repo to run tests and some others
#
# Usage: ./run.sh [OPTIONS] /path/to/git/directory
# Available options :
#   -h|--help:    Display memo of params
#   -i|--init:    Initialize your test directory (default mode)
#   -u|--update:  Update your test directory
# /!\ run this command from your test directory /!\
#

run_usage()
{
  echo "Usage: ./run.sh [OPTIONS] /path/to/git/directory
 Available options :
   -h|--help:    Display memo of params
   -i|--init:    Initialize your test directory (default mode)
   -u|--update:  Update your test directory
 /!\ run this command from your test directory /!\ "
}

run_init()
{
  if [ -d $1 ]
  then
    echo "Starting init..."
    cp $1/src/* ./
    cp $1/lpi.txt ./
  else
    echo "$1 is not a valid path !!!"
    exit 10
  fi
  sqlite3 --init init.sql lpic_quizz.db
  echo "Step finished"
}

run_update()
{
  echo "Starting update..."
  if [ -f update_db.py ]
  then
    ./update_db.py
    res=$?
    if [ $res -ne 0 ]
    then
      echo "Some errors arises in DB update"
      exit $res
    fi
    echo "Step finished"
  else
    echo "This is not a working directory oO"
    exit 11
  fi
}

MODE=0 # 0=init, 1=update

for param in $*
do
  case $param in
    -h)
      run_usage
      exit 0
      ;;
    --help)
      run_usage
      exit 0
      ;;
    -i)
      MODE=0
      ;;
    --init)
      MODE=0
      ;;
    -u)
      MODE=1
      run_update
      echo "Finished"
      exit 0
      ;;
    --update)
      MODE=1
      run_update
      echo "Finished"
      exit 0
      ;;
    *)
      if [ $MODE -eq 0 ]
      then
        run_init $param
      fi
      run_update
      echo "Finished"
      exit 0
      ;;
  esac
done

echo "Where are params ?!?"
run_usage
exit 12
