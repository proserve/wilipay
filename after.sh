#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.
echo "alias xon='sudo phpenmod -s cli xdebug'" >> /home/vagrant/.profile
echo "alias xoff='sudo phpdismod -s cli xdebug'" >> /home/vagrant/.profile
sudo phpenmod -s cli xdebug