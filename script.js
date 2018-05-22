function connectDB(){
    conn = new ActiveXObject("ADODB.Connection");
    var connString = "Data Source=localhost/XE;User ID=tw;Password=TW;Provider=SQLOLEDB";
    conn.Open(connString);
    readSearch = new ActiveXObject("ADODB.Recordset");
}
function passForUser(userName, passw){
    connectDB()
    readSearch.Open("Select user_name, pass from jucator", conn)
    readSearch.MoveFirst;
    while(!readSearch.eof){
        if ( userName.localeCompare(readSearch.fields(1)) == 0 && passw.localeCompare(readSearch.fields(2)) == 0 ){
            return true
        }
        readSearch.movenext
    }
    readSearch.close;
    conn.close;
    return false
}
function Login(userName, passw){
    if ( passForUser(userName, passw)){
            
    }
    else{
        alert("Wrong Username or Password!")
    }
}