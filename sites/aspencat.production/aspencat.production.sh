#!/bin/sh

if [ -z "$1" ]
  then
    echo "To use, run with start, stop or restart for the first parameter."
fi

if [[ ( "$1" == "stop" ) || ( "$1" == "restart") ]]
  then
    /usr/local/aspen-discovery/sites/default/solr-8.11.2/bin/solr stop -p 8080 -s "/data/aspen-discovery/aspencat.production/solr7" -d "/usr/local/aspen-discovery/sites/default/solr-8.11.2/server"
fi

if [[ ( "$1" == "start" ) || ( "$1" == "restart") ]]
  then
    /usr/local/aspen-discovery/sites/default/solr-8.11.2/bin/solr start -m 14g -p 8080 -s "/data/aspen-discovery/aspencat.production/solr7" -d "/usr/local/aspen-discovery/sites/default/solr-8.11.2/server"
fi
