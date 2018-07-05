```
<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=hengrui','root','root',[PDO::ATTR_PERSISTENT => true]);
$pdo->query('set names utf8');
$statement = $pdo->query('select * from area limit 2');

$res = $statement->fetchAll(PDO::FETCH_ASSOC);
print_r($res);die();
/*
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
	print_r($row);
}
*/

$update_sql = 'update area set Name=\'江西省\' where id = :id';
$update_statement = $pdo->prepare($update_sql);
$update_statement->bindValue(':id', $res[0]['id'], PDO::PARAM_INT);
$flag = $update_statement->execute();
print_r($flag);

// =========================================================
/*
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/
$db=new PDO('mysql:dbname=dbname;host=127.0.0.1','root','123456');
$db->exec("set names utf8");
//增
$name1='测试的例子love1';
$name2='测试的例子love2';
$add=$db->prepare("insert into test(name) values(?),(?)");
$add->execute(array($name1,$name2));
//删
$id=1;
$delete=$db->prepare("delete from test where id=$id");
$delete->execute();
//改
$id2=4;
$nameupdate='修改后的';
$update=$db->prepare("update test set name=? where id=?");
$update->execute(array($nameupdate,$id2));
//查
$limit =3;
$list=$db->query("select id,name from test  limit $limit")->fetchAll();
foreach($list as $v){
	echo $v['id'].'--'.$v['name']."\r\n";
}

```
