<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="api/api_book_table.php" method="post">
        <input type="text" name="cf" placeholder="Codice Fiscale" required>
        <input type="text" name="cognome" placeholder="Cognome" required>
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="tel" name="telefono" placeholder="Telefono" required>
        <input type="text" name="indirizzo" placeholder="Indirizzo">
        <input type="date" name="data" placeholder="Data" required>
        <input type="time" name="ora" placeholder="Ora" required>
        <input type="number" name="n_persone" placeholder="Numero persone" required>
        <textarea name="note_aggiuntive" placeholder="Note"></textarea>
        <button type="submit" name="submit">Vai</button>
    </form>
</body>
</html>