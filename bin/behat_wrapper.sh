#!/bin/bash

BEHAT_COMMAND_ARGS=
if [ -z ${JENKINS_FEATURES_FAIL_FAST+x} ]; then
    JENKINS_FEATURES_FAIL_FAST=true
else
    if [ "$JENKINS_FEATURES_FAIL_FAST" = "true" ] || [ "x$JENKINS_FEATURES_FAIL_FAST" = "x" ]; then
        JENKINS_FEATURES_FAIL_FAST=true
    else
        JENKINS_FEATURES_FAIL_FAST=false
    fi
fi

if $JENKINS_FEATURES_FAIL_FAST ; then
    set -e
    BEHAT_COMMAND_ARGS=' --stop-on-failure '
else
    set +e
fi

EXIT_STATUS=0

run_behat() {
    if [[ $1 == */wip_*.feature ]] || [[ $1 == */skip_*.feature ]]; then
        return
    fi
    echo "Running: $1";
    php -d memory_limit=-1 vendor/bin/behat --format progress -vvv $BEHAT_COMMAND_ARGS $1
    EXIT_CODE=$?
    if [ "$EXIT_CODE" -ne "0" ]; then
        EXIT_STATUS=$EXIT_CODE
    fi
}

recurse_dir() {
    if [ -d "$1" ]
    then
        for subdir in $1/*; do
            if [ "${subdir}" == "features/bootstrap" ]; then
                continue
            fi
            recurse_dir $subdir
        done
    else
        run_behat $1
    fi
}

recurse_dir features
exit $EXIT_STATUS