#!/bin/bash
# Azure App Service (Linux, PHP, nginx) startup command:  /home/site/wwwroot/startup.sh
# Adds docker/nginx-hardening.conf to the stock nginx site and hides X-Powered-By. If the edited config
# does not pass "nginx -t" the stock one is restored, so a mistake here can never take the site down.
CONF=/etc/nginx/sites-available/default
SNIP=/home/site/wwwroot/docker/nginx-hardening.conf

[ -f "$CONF.orig" ] || cp "$CONF" "$CONF.orig"
cp "$CONF.orig" "$CONF"

if [ -f "$SNIP" ]; then
  awk -v inc="    include $SNIP;" '
    { print }
    !inc_done && /^[ \t]*root[ \t].*;/ { print inc; inc_done = 1 }
    !hdr_done && /fastcgi_intercept_errors/ { print "        fastcgi_hide_header X-Powered-By;"; hdr_done = 1 }
  ' "$CONF.orig" > "$CONF"
  if ! nginx -t >/dev/null 2>&1; then
    echo "nginx-hardening: config test failed, restoring the stock config" >&2
    cp "$CONF.orig" "$CONF"
  fi
fi

service nginx reload