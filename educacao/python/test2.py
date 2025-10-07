# encoding: iso-8859-1
import mysql.connector
import config
try:    
    connection = mysql.connector.connect(**config.mysql)
    cursor = connection.cursor(prepared=True)    
    sql_insert_query = """ INSERT INTO estado(id, nome) VALUES (%s, %s)"""                       
    tuple1 = (8, "X1")
    tuple2 = (9, "Y1")
    cursor.execute(sql_insert_query, tuple1)
    cursor.execute(sql_insert_query, tuple2)    
    connection.commit()
    print("Consulta realizada com sucesso!!")
except mysql.connector.Error as error:
    print("Consulta parametrizada não realizada!<br><br>".format(error))    
finally:
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("A conexão com o MySQL foi devidamente fechada.")