date=`date +%Y%m%d`
dateFormatted=`date -R`
s3Bucket="save-k9"
fileName="exemple.txt"
relativePath="/${s3Bucket}/${fileName}"
contentType="application/octet-stream"
stringToSign="PUT\n\n${contentType}\n${dateFormatted}\n${relativePath}"
s3AccessKey="0EG566KNTBMJESVF567O"
s3SecretKey="VpVqdnFNXO6/A3JdMDeIl33tKpMJ9eZeLkXLDIeW"
signature=`echo -en ${stringToSign} | openssl sha1 -hmac ${s3SecretKey} -binary | base64`


case  $1 in
	put)
curl -k -L \
-H "Host: scality-s3.gendarmerie.fr" \
-H "Date: ${dateFormatted}" \
-H "Content-Type: ${contentType}" \
-H "Authorization: AWS ${s3AccessKey}:${signature}" \
-X PUT -T "${fileName}" "https://scality-s3.gendarmerie.fr/${s3Bucket}/${fileName}" \
-v
	;;
	
	get)
curl -k -L \
-H "Host: scality-s3.gendarmerie.fr" \
-H "Date: ${dateFormatted}" \
-H "Content-Type: ${contentType}" \
-H "Authorization: AWS ${s3AccessKey}:${signature}" \
-X GET "https://scality-s3.gendarmerie.fr/${s3Bucket}/${fileName}" \
-o "/tmp/${fileName}" \
-v
      

	;;
esac
