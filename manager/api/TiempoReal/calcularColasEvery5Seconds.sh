i=0

while [ $i -lt 12 ]; do # 12 five-second intervals in 1 minute
  php /var/www/html/manager/api/TiempoReal/calcularColas.php
  sleep 5
  i=$(( i + 1 ))
done