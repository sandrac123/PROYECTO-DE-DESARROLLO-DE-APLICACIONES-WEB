<!DOCTYPE html>
<html lang="gl">

<head>
  <meta charset="UTF-8">
  <title>Engadir Pregunta á Enquisa</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(120deg, #feda75, #fa7e1e, #d62976, #962fbf, #4f5bd5);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      padding: 40px;
      color: #333;
    }

    @keyframes gradientBG {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    h2 {
      text-align: center;
      background: linear-gradient(45deg, #feda75, #fa7e1e, #d62976, #962fbf, #4f5bd5);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 2.2em;
      margin-bottom: 30px;
    }

    form {
      background: rgba(91, 219, 215, 0.95);
      border-radius: 20px;
      padding: 30px 40px;
      max-width: 500px;
      margin: auto;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-weight: bold;
      color: #444;
    }

    input[type="text"],
    select {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 10px;
      background: #f3f3f3;
      color: #333;
      font-size: 1em;
      box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    input::placeholder {
      color: #999;
    }

    select option {
      background-color: white;
      color: #333;
    }

    button {
      background: linear-gradient(45deg, #feda75, #df8f52, #d62976, #962fbf, #4f5bd5);
      color: white;
      padding: 12px 20px;
      font-size: 1em;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      width: 100%;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    button:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>

<body>
  <h2>Engadir Pregunta á Enquisa</h2>
  <form>

    <label for="enquisa_id">Selecciona unha enquisa:</label>
    <select name="enquisa_id" id="enquisa_id" required>
      <option value="">-- Escolle unha enquisa --</option>
      <option value="1">Enquisa 1</option>
      <option value="2">Enquisa 2</option>
      <option value="3">Enquisa 3</option>
    </select>

    <label for="texto">Texto da pregunta:</label>
    <input type="text" id="texto" name="texto" placeholder="Introduce a pregunta" required>

    <label for="tipo">Tipo de pregunta:</label>
    <select name="tipo" id="tipo" required>
      <option value="">-- Escolle un tipo --</option>
      <option value="unica">Opción única</option>
      <option value="multiple">Opción múltiple</option>
      <option value="libre">Texto libre</option>
    </select>

    <button type="submit">Engadir Pregunta</button>
  </form>
</body>

</html>