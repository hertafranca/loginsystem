# Laboratório de SQL Injection

Demonstração didática de **SQL Injection**: o mesmo login em duas versões — uma vulnerável e uma protegida — para mostrar o ataque e perceber a diferença entre concatenar input e usar *prepared statements*.

> ⚠️ Código intencionalmente vulnerável, só para aprendizagem. Não usar em produção.

## Ficheiros

| Ficheiro | Descrição |
|----------|-----------|
| `login.php` | Versão **vulnerável** (query construída por concatenação) |
| `injection.php` | Versão **protegida** (prepared statements) |
| `database.sql` | Script para criar a BD `injection_lab` e a tabela `users` |

## Como montar (XAMPP)

1. Abrir o **XAMPP** e ligar **Apache** e **MySQL**.
2. Ir a `http://localhost/phpmyadmin` e importar/correr o `database.sql`.
3. Copiar os ficheiros `.php` para uma pasta dentro de `htdocs`, por exemplo `htdocs/injection-lab/`.
4. Abrir no browser:
   - Vulnerável: `http://localhost/injection-lab/login.php`
   - Protegida: `http://localhost/injection-lab/injection.php`

## Como testar a injeção

Na versão **vulnerável** (`login.php`), no campo **Username** escrever:

```
' OR '1'='1' --
```

(com um espaço depois do `--`). A query passa a ser:

```sql
SELECT * FROM users WHERE username = '' OR '1'='1' -- ' AND password = ''
```

O `'1'='1'` é sempre verdadeiro e o `--` comenta a verificação da password → **login sem credenciais**.

Para entrar como um utilizador específico, no Username:

```
admin' --
```

Na versão **protegida** (`injection.php`), as mesmas entradas são tratadas como texto literal e o login falha. A injeção deixa de funcionar.

## Porque é que uma é segura

A versão protegida usa prepared statements:

```php
$stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $user, $pass);
```

Os `?` garantem que o input é enviado como **dados**, nunca como SQL.

## Boas práticas (além do exercício)

- Guardar passwords com `password_hash()` e verificar com `password_verify()`, nunca em texto simples.
- Nunca mostrar a password nem o SQL ao utilizador final (aqui é só para ver o ataque).
