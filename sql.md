防止sql注入
mysql_real_escape_string

黄绍瑞 2015/11/26 12:18:15
mysql_real_escape_string

(PHP 4 >= 4.3.0, PHP 5)

mysql_real_escape_string — 转义 SQL 语句中使用的字符串中的特殊字符，并考虑到连接的当前字符集 


说明 ?

string mysql_real_escape_string ( string $unescaped_string [, resource $link_identifier ] )

本函数将 unescaped_string 中的特殊字符转义，并计及连接的当前字符集，因此可以安全用于 mysql_query()。 


Note: mysql_real_escape_string() 并不转义 % 和 _。 

