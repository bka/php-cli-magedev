#!/bin/bash

# Start varnish and log
# varnishd -f /etc/varnish/default.vcl -s malloc,100M -a 0.0.0.0:6082 -b 0.0.0.0:80

# echo -n varnish | sha256sum > /etc/varnish/secret
# chmod -R 600 /etc/varnish/secret
# varnishd -f /etc/varnish/default.vcl -S /etc/varnish/secret -s malloc,100M -T 0.0.0.0:6082 -p vcc_allow_inline_c=on
varnishd -S none -f /etc/varnish/default.vcl -s malloc,100M -T 0.0.0.0:6082 -p vcc_allow_inline_c=on -p feature=+esi_ignore_other_elements

# varnishlog
