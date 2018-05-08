DROP TABLE jucator CASCADE CONSTRAINTS
/
DROP TABLE valute CASCADE CONSTRAINTS
/
DROP TABLE joc CASCADE CONSTRAINTS
/
DROP TABLE scor CASCADE CONSTRAINTS
/

CREATE TABLE jucator (
  user_name VARCHAR2(20) NOT NULL PRIMARY KEY,
  pass VARCHAR2(15) NOT NULL,
  email VARCHAR2(30) NOT NULL)
/


CREATE TABLE valute (
  nume_valuta VARCHAR2(50) NOT NULL,
  folosit NUMBER(1)
)
/

CREATE TABLE joc (
  durata_unei_valute NUMBER(2),
  banii_initiali NUMBER(10),
  prag_superior NUMBER(10),
  prag_inferior NUMBER(5),
  marja_minima NUMBER(5),
  marja_maxima NUMBER(10)
)
/

CREATE TABLE scor (
  user_name VARCHAR2(20) NOT NULL,
  highscore  NUMBER(20),
  data_scor  DATE,
  CONSTRAINT fk_scor FOREIGN KEY (user_name) REFERENCES jucator(user_name)
)
/

insert into jucator(user_name,pass,email) values('vasile','parola','scuz@vrb.com');
insert into jucator(user_name,pass,email) values('ion','parol1','email');

insert into valute(nume_valuta,folosit) values('euro',1);
insert into valute(nume_valuta,folosit) values('ron',1);
insert into valute(nume_valuta,folosit) values('dolar',1);
insert into valute(nume_valuta,folosit) values('lira',1);

insert into joc(durata_unei_valute, banii_initiali,prag_superior,prag_inferior,marja_minima,marja_maxima) values(30,500,2000,100,2,6);

insert into scor(user_name,highscore,data_scor) values('ion',3000,sysdate);
insert into scor(user_name,highscore,data_scor) values('vasile',2300,sysdate);
insert into scor(user_name,highscore,data_scor) values('vasile',9999,sysdate + 1);