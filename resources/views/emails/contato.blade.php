<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Contato — Paróquia Nossa Senhora da Glória</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;">
        <h2 style="color:#1a3a5c;">Nova mensagem recebida pelo site</h2>

        <p><strong>De:</strong> {{ $nomeRemetente }} &lt;{{ $emailRemetente }}&gt;</p>
        <p><strong>Assunto:</strong> {{ $assunto }}</p>

        <hr>

        <h3 style="color:#1a3a5c;">Mensagem</h3>
        <p style="white-space: pre-wrap;">{{ $mensagem }}</p>

        <hr>

        <small style="color:#888;">
            Mensagem enviada pelo formulário de contato do site da Paróquia Nossa Senhora da Glória.
        </small>
    </div>
</body>
</html>
