<?php
/**
 * PHPRedis PHP Redis documentation
 * 
 * CONNECTION
 * @method bool connect(string $host, int $port, float $timeout, int $retry_interval) Connects to a Redis instance.
 * @method bool open(string $host, int $port, float $timeout, int $retry_interval) Connects to a Redis instance.
 * @method bool auth(string $password) Authenticate the connection using a password. Warning: The password is sent in plain-text over the network.
 * STRING
 * @method bool exists(string $key) Chequea la existencia de una clave
 * @method string get(string $key) Obtiene el valor de una clave
 * @method bool set(string $key, string $value) Set the string value in argument as value of the key. If you're using Redis >= 2.6.12, you can pass extended options as explained below.
 * HASH
 * @method mixed hSet(string $key, string $hashKey, mixed $value) Adds a value to the hash stored at key. If this value is already in the hash, FALSE is returned.
 * @method mixed hGet() Gets a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
 * @method array hGetAll(string $key) Returns the whole hash, as an array of strings indexed by strings.
 * SET
 * @method array sDiff(string $key1 keys identifying sets, string $key2 keys identifying sets, ...) Performs the difference between N sets and returns it.
 * @method array sInter(string $key1 keys identifying sets, string $key2 keys identifying sets, ...) Returns the members of a set resulting from the intersection of all the sets held at the specified keys.
 * TRANSACTION
 * @method bool multi() Abre un Statement para hacer multiples peticiones
 * @method array exec() Ejecuta el Statement
 */
class Redis
{
  //ApiDoc PHPRedis no se debe incluir esta clase en ningun lado. Solo existe para documentar el wrapper de redis
}
