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
    while(!readSearch.eof)
    {
        if ( userName.localeCompare(readSearch.fields(1)) == 0 && passw.localeCompare(readSearch.fields(2)) == 0 )
        {
            return true
        }
        readSearch.movenext
    }
    readSearch.close;
    conn.close;
    return false
}
function Login(){
    var userName = document.getElementById("userName")
    if ( userName != null )
    {
        var passw = document.getElementById("passw")
        if ( passw != null)
        {
            if ( passForUser(userName, passw) )
            {
                window.location.replace("game.html")
            }
            else
            {
                alert("Wrong Username or Password!")
            }
        }
        else
        {
            alert("Missing Password!")
        }
    }
    else
    {
        alert("Missing Username!")
    }
}

function existingUsername(userName)
{
    connectDB()
    readSearch.Open("Select user_name from jucator", conn)
    readSearch.MoveFirst;
    while(!readSearch.eof)
    {
        if ( userName.localeCompare(readSearch.fields(1)) )
        {
            return false
        }
        readSearch.movenext
    }
    readSearch.close;
    conn.close;
    return true
}

function usedMail(mail)
{
    connectDB()
    readSearch.Open("Select email from jucator", conn)
    readSearch.MoveFirst;
    while(!readSearch.eof)
    {
        if ( mail.localeCompare(readSearch.fields(1)) )
        {
            return false
        }
        readSearch.movenext
    }
    readSearch.close;
    conn.close;
    return true
}

function registerDB(userName, passw, mail)
{
    connectDB()
    command = "insert into jucator(user_name, pass, email) values (" + String(userName) + "," + String(passw) + "," + String(mail) + ")"
    readSearch.Open(command, conn)
    readSearch.close
    conn.close
}

function validRegister(userName, passw, mail)
{
    if ( existingUsername(userName) )
    {
        return false
    }
    if ( usedMail(mail) )
    {
        return false
    }
    registerDB(userName, passw, mail)
    return true
}

function Register()
{
    var userName = document.getElementById("registerUserName")
    if ( userName != null )
    {
        var passw = document.getElementById("registerPassw")
        if ( passw != null )
        {
            var mail = document.getElementById("registerEmail")
            if ( mail != null )
            {
                if ( validRegister(userName, passw, mail) )
                {
                    window.location.replace("game.html")
                }
            }
            else
            {
                alert("Missing Email!")
            }
        }
        else
        {
            alert("Missing Password!")
        }
    }
    else
    {
        alert("Missing Username!")
    }
}

function valuteDB(folDolar, folEuro, folLira, folYen)
{
    connectDB()
    if (folDolar)
    {
        var d = 1
    }
    else
    {
        var d = 0
    }
    var comDolar = "Update valute set folosit = " + String(d) + "where nume_valuta = \'dolar\'"
    readSearch.Open(comDolar, conn)
    if (folEuro)
    {
        var e = 1
    }
    else
    {
        var e = 0
    }
    var comEuro = "Update valute set folosit = " + String(e) + "where nume_valuta = \'euro\'"
    readSearch.Open(comEuro, conn)
    if (folYen)
    {
        var y = 1
    }
    else
    {
        var y = 0
    }
    var comYen = "Update valute set folosit = " + String(y) + "where nume_valuta = \'yen\'"
    readSearch.Open(comYen, conn)
    if (folLira)
    {
        var l = 1
    }
    else
    {
        var l = 0
    }
    var comLira = "Update valute set folosit = " + String(l) + "where nume_valuta = \'lira\'"
    readSearch.Open(comLira, conn)
    readSearch.close
    conn.close
}

function setareValute()
{
    var folDolar = document.getElementById("administratorDolar").checked
    var folEuro = document.getElementById("administratorEuro").checked
    var folLira = document.getElementById("administratorLira").checked
    var folYen = document.getElementById("administratorYen").checked
    if ( !folDolar && !folEuro && !folLira && !folYen )
    {
        alert("No money used? Impossible!")
    }
    else
    {
        valuteDB(folDolar, folEuro, folLira, folYen)
        alert("Valutes modified!")
    }
}