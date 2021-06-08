# lightning_charge
Provides lightning charge integration for Drupal.

You must have a running bitcoin full node, clightning, and lightning charge daemon.

This has been developed using the bitcoin testnet.

https://github.com/ElementsProject/lightning
https://github.com/ElementsProject/lightning-charge

Screenshots at: https://photos.app.goo.gl/rccz95k8HRJcmnCP9

/Applications/Bitcoin-Qt.app/Contents/MacOS/Bitcoin-Qt --testnet --printtoconsole -daemon
lightningd

# lightning-charge daemon must be run with:
NODE_TLS_REJECT_UNAUTHORIZED=0 yarn start --allow-cors="https://lightning.localhost"

nginx config for payments.lightning.localhost

server {
    listen 443 ssl;
    server_name payments.lightning.localhost;

    error_log /var/log/nginx/payments-lightning.error.log;

    ssl_certificate /Users/md/Websites/personal/lightning/ssl/artifacts/wildcard.crt;
    ssl_certificate_key /Users/md/Websites/personal/lightning/ssl/secrets/signing.key;

    #add_header 'Access-Control-Allow-Origin' 'https://lightning.localhost';
    # echo -n api-token:base64passwordgoeshere | base64

    location / {
      proxy_set_header Host $host;
      proxy_set_header X-Real-IP $remote_addr;
      proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_pass http://localhost:9112;
      proxy_set_header Authorization "Basic [base64passwordgoeshere]";

      proxy_http_version 1.1;
      proxy_set_header Upgrade $http_upgrade;
      proxy_set_header Connection "Upgrade";
   }
}

server {
  listen 80;
  server_name payments.lightning.localhost;
  return 301 https://payments.lightning.localhost$request_uri;
}
