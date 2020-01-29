const app = document.getElementById('root') //main app del html
const authenticate = document.createElement('pre') // se crea un elemento de tipo pre
authenticate.setAttribute('id', 'auth')
app.appendChild(authenticate); //se agrega al main root el elemnto.
var TOKEN = ''; //variable que almacena el token.
const hubs = document.createElement('button') // se crea un elmento buton de html


//Función que manda a llamar el Auth que regresa el Token.
function authorize(){
 var http = new XMLHttpRequest();
 var url = 'https://developer.api.autodesk.com/authentication/v1/authenticate';
 var params = 'client_id=kA3Ut8cWUgbpubQNAqM566iFqc82of7o&client_secret=TCI2jSd9BGRjuHvi&grant_type=client_credentials&scope=data:read';
 http.open('POST', url, true);
 http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

 http.onreadystatechange = function() {
 if(http.readyState == 4 && http.status == 200) {
   //Guardamos el Response en un variable para manipular dato.
  var data = JSON.parse(http.responseText);
  //Convertimos el response para se puede imprimr todo en formato pretty json
  var jsonPretty = JSON.stringify(JSON.parse(http.responseText),null,2);

  auth = document.getElementById('auth'); //Obtenemos el elemento html auth creado anteriormente.
   TOKEN = data.access_token; //Le pasamos a TOKEN el token de forge.
   auth.append(jsonPretty) // Imprimios todo el JSON

   //creamos otro elemento de tipo boton con función a llamar hubs, le pasamos el token.
   hubs.setAttribute('type', 'button');
   hubs.setAttribute('name','hubs');
   hubs.setAttribute('onclick','GetHubs("'+TOKEN+'")');
   hubs.append('Obtener Hubs')
   app.appendChild(hubs); //Agregamos el boton a nuestra app principal-
 }

 }
 http.send(params);
}

//función al hacer click el Buton Obtener Hubs mandamos a llamar esta función.
function GetHubs(llave){
var request = new XMLHttpRequest()
request.open('GET', 'https://developer.api.autodesk.com/project/v1/hubs', true);
request.setRequestHeader('Authorization', 'Bearer ' + llave); //le pasamos el token.
request.onload = function() {

 if (request.status >= 200 && request.status < 400) {
     //Guardamos el Response en un variable para manipular dato.
   var info = JSON.parse(request.responseText);
   console.log(info);
   //Convertimos el response para se puede imprimr todo en formato pretty json
   var jsonPretty = JSON.stringify(JSON.parse(request.responseText),null,2);
   const ghubs = document.createElement('pre'); //creamos un elemento PRE de html
   app.appendChild(ghubs); //Lo anexemos al main app
   ghubs.append(jsonPretty); //E imprimimos en el el JSON completo.
   const h3 = document.createElement('h3'); //cremoas un elemento de tipo h3
   app.appendChild(h3); //lo anexamos al main app
   h3.append('Empresa: ' + info.data[0].attributes.name); //le imprimimos el valor especifico del Name

 } else {
   console.log('error')
 }
}

request.send()
}
