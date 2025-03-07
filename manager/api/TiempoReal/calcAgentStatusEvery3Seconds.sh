i=0

while [ $i -lt 20 ]; do # 20 three-second intervals in 1 minute
  php /var/www/html/manager/api/TiempoReal/estadosAgentes.php calcAgentStatus
  sleep 3
  echo $i
  i=$(( i + 1 ))
done