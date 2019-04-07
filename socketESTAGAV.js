var http = require("http").createServer(servidor);
var io = require("socket.io").listen(http);
var fs = require("fs");

var usuarios = 0;

function servidor(req, res){
	res.writeHead(200);
	//res.end(fs.readFileSync("index.php"));
}



io.on("connection", function(socket){
	socket.join(socket.handshake.query.userid);
	console.log("Usuário Conectado com o id: ",socket.handshake.query.userid);
	usuarios++;
	console.log("Users online: ",usuarios);
	
	socket.on("update", function(msg){
		io.to(msg).emit("update", msg);
		console.log("Update no lobby: ", msg);
	});
	
	socket.on("status", function(msg){
		console.log(socket.adapter.rooms);
	});
	
	
	
	

	socket.on("disconnect", function(){
		console.log("Usuario Desconectado");
		usuarios--;
	});
});


http.listen(4002);
