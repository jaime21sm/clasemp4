<?php
// home.php CORREGIDO - Reemplaza tu archivo actual con este

require_once 'conexion.php'; // Trae la conexión aquí

$seccion_actual = isset($_GET['seccion']) ? $_GET['seccion'] : 'inicio';
$mensaje_db = "";
?>
<?php
$seccion_actual = isset($_GET['seccion']) ? $_GET['seccion'] : 'inicio';
?>
<?php
/*
 * Ghost Hunter's Den - Fan zone de Phasmophobia
 */
session_start();

/* =====================================================================
   1. GRÁFICOS SVG
   ===================================================================== */
function svgCasaEmbrujada() {
    return '<svg viewBox="0 0 600 300" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Casa embrujada bajo la luna">
        <rect width="600" height="300" fill="#060a08"/>
        <circle cx="500" cy="60" r="38" fill="#e9edc9" opacity="0.9"/>
        <circle cx="488" cy="50" r="38" fill="#060a08"/>
        <g fill="#0e1512" stroke="#4dff9e" stroke-width="2">
            <polygon points="150,160 300,60 450,160" />
            <rect x="180" y="160" width="240" height="120" />
        </g>
        <rect x="270" y="200" width="60" height="80" fill="#060a08" stroke="#ff2e4d" stroke-width="2"/>
        <rect x="205" y="185" width="35" height="35" fill="#ffcf6b" opacity="0.85"/>
        <rect x="360" y="185" width="35" height="35" fill="#4dff9e" opacity="0.35"/>
        <g stroke="#8fa89f" stroke-width="2">
            <path d="M40,290 C80,260 120,290 160,270" fill="none"/>
            <path d="M420,290 C460,265 500,290 560,270" fill="none"/>
        </g>
        <g fill="#0e1512">
            <ellipse cx="80" cy="95" rx="10" ry="4"/>
            <ellipse cx="105" cy="80" rx="8" ry="3"/>
            <ellipse cx="130" cy="100" rx="9" ry="3"/>
        </g>
    </svg>';
}
function svgEquipo($tipo) {
    switch ($tipo) {
        case 'emf':
            return '<svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg"><rect x="20" y="20" width="80" height="90" rx="8" fill="#0e1512" stroke="#4dff9e" stroke-width="3"/><rect x="32" y="32" width="56" height="30" fill="#060a08" stroke="#ffcf6b" stroke-width="2"/><circle cx="45" cy="47" r="4" fill="#ff2e4d"/><circle cx="60" cy="47" r="4" fill="#ffcf6b"/><circle cx="75" cy="47" r="4" fill="#4dff9e"/><circle cx="60" cy="85" r="14" fill="#060a08" stroke="#4dff9e" stroke-width="3"/></svg>';
        case 'camara':
            return '<svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg"><rect x="15" y="35" width="90" height="55" rx="8" fill="#0e1512" stroke="#ff2e4d" stroke-width="3"/><circle cx="60" cy="62" r="22" fill="#060a08" stroke="#ffcf6b" stroke-width="3"/><circle cx="60" cy="62" r="10" fill="#4dff9e" opacity="0.5"/><rect x="40" y="22" width="40" height="15" fill="#0e1512" stroke="#ff2e4d" stroke-width="2"/></svg>';
        case 'linterna':
            return '<svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg"><rect x="35" y="20" width="30" height="60" rx="6" fill="#0e1512" stroke="#4dff9e" stroke-width="3"/><polygon points="35,20 65,20 78,5 22,5" fill="#0e1512" stroke="#4dff9e" stroke-width="3"/><polygon points="30,85 70,85 95,115 5,115" fill="#ffcf6b" opacity="0.25"/></svg>';
        default:
            return '<svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg"><rect x="52" y="10" width="16" height="70" rx="8" fill="#0e1512" stroke="#4dff9e" stroke-width="3"/><circle cx="60" cy="92" r="18" fill="#060a08" stroke="#ff2e4d" stroke-width="3"/><rect x="56" y="30" width="8" height="50" fill="#ff2e4d"/><circle cx="60" cy="92" r="9" fill="#ff2e4d"/></svg>';
    }
}
function svgFantasma($color) {
    return '<svg viewBox="0 0 120 140" xmlns="http://www.w3.org/2000/svg"><path d="M60 10 C25 10 15 40 15 65 L15 120 L30 105 L45 120 L60 105 L75 120 L90 105 L105 120 L105 65 C105 40 95 10 60 10 Z" fill="' . $color . '" opacity="0.85"/><circle cx="42" cy="60" r="7" fill="#060a08"/><circle cx="78" cy="60" r="7" fill="#060a08"/><ellipse cx="60" cy="80" rx="10" ry="6" fill="#060a08" opacity="0.6"/></svg>';
}
function svgHabitacion() {
    return '<svg viewBox="0 0 400 240" xmlns="http://www.w3.org/2000/svg"><rect width="400" height="240" fill="#060a08"/><polygon points="120,0 280,0 400,240 0,240" fill="#ffcf6b" opacity="0.06"/><rect x="60" y="40" width="60" height="90" fill="#0e1512" stroke="#4dff9e" stroke-width="2"/><rect x="280" y="60" width="50" height="70" fill="#0e1512" stroke="#ff2e4d" stroke-width="2"/><circle cx="200" cy="170" r="18" fill="#4dff9e" opacity="0.5"/><rect x="0" y="210" width="400" height="30" fill="#0e1512"/></svg>';
}

/* =====================================================================
   2. LÓGICA
   ===================================================================== */
$vistas_validas = ['entrada', 'bienvenida', 'inicio', 'sobre-el-juego', 'fantasmas', 'galeria', 'contacto'];
$vista = $_GET['vista'] ?? (isset($_SESSION['investigador']) ? 'inicio' : 'entrada');
if (!in_array($vista, $vistas_validas, true)) {
    $vista = 'inicio';
}

if ($vista === 'entrada' && $_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_investigador = trim($_POST['investigador'] ?? '');
    if ($nombre_investigador !== '') {
        $_SESSION['investigador'] = $nombre_investigador;
        header("Location: ?vista=bienvenida");
        exit();
    }
}
if ($vista === 'bienvenida' && !isset($_SESSION['investigador'])) {
    header("Location: ?vista=entrada");
    exit();
}

// --- CONTACTO: AQUÍ ESTABA EL ERROR - FALTABA EL INSERT ---
$mensaje_db = "";
if ($vista === 'contacto' && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nombre'])) {
    $nombre_contacto   = trim($_POST['nombre']);
    $correo_contacto   = trim($_POST['correo'] ?? '');
    $fantasma_favorito = trim($_POST['fantasma_favorito'] ?? '');
    $mensaje_texto     = trim($_POST['mensaje'] ?? '');

    if ($nombre_contacto !== '' && $correo_contacto !== '' && $fantasma_favorito !== '' && $mensaje_texto !== '') {
        if (filter_var($correo_contacto, FILTER_VALIDATE_EMAIL)) {
            
            // ==== CORRECCIÓN: GUARDAR EN LA BASE DE DATOS ====
            try {
                $sql = "INSERT INTO mensajes (nombre, correo, fantasma_favorito, mensaje, fecha_envio) 
                        VALUES (:nombre, :correo, :fantasma, :mensaje, NOW())";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre_contacto,
                    ':correo' => $correo_contacto,
                    ':fantasma' => $fantasma_favorito,
                    ':mensaje' => $mensaje_texto
                ]);
                $mensaje_db = "<p class='aviso-exito'>¡Gracias, " . htmlspecialchars($nombre_contacto) . "! Tu mensaje fue recibido y guardado.</p>";
            } catch (PDOException $e) {
                $mensaje_db = "<p class='aviso-error'>Error al guardar: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            // ==== FIN CORRECCIÓN ====

        } else {
            $mensaje_db = "<p class='aviso-error'>Por favor, introduce un correo electrónico válido.</p>";
        }
    } else {
        $mensaje_db = "<p class='aviso-error'>Todos los campos son obligatorios.</p>";
    }
}

$es_pantalla_caja = in_array($vista, ['entrada', 'bienvenida'], true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ghost Hunter's Den | Fan Zone de Phasmophobia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root { --fondo:#060a08; --panel:#0e1512; --verde-fantasma:#4dff9e; --rojo-peligro:#ff2e4d; --ambar-linterna:#ffcf6b; --texto:#d7e3dd; }
        *{box-sizing:border-box;}
        body{font-family:'Rubik',Arial,sans-serif;margin:0;padding:0;background-color:var(--fondo);background-image:radial-gradient(circle at 15% 10%, rgba(77,255,158,0.06) 0, transparent 45%),radial-gradient(circle at 85% 90%, rgba(255,46,77,0.06) 0, transparent 45%);color:var(--texto);}
        body.pantalla-caja{display:flex;align-items:center;justify-content:center;min-height:100vh;}
        .caja{max-width:460px;width:90%;padding:35px;background-color:var(--panel);border:1px solid rgba(77,255,158,0.25);border-radius:10px;text-align:center;box-shadow:0 0 30px rgba(0,0,0,0.6);}
        .caja h1{color:var(--verde-fantasma);text-shadow:0 0 10px rgba(77,255,158,0.7);font-size:1.7rem;}
        .caja input{width:100%;margin-top:15px;}
        input,textarea{width:100%;background:#000;border:1px solid var(--verde-fantasma);color:#fff;padding:10px;border-radius:6px;margin-top:5px;}
        button{width:100%;margin-top:20px;background:var(--rojo-peligro);color:#fff;border:none;padding:12px;border-radius:6px;font-weight:bold;cursor:pointer;}
        button:hover{background:#ff3b57;}
        .aviso-exito{background:rgba(0,255,136,0.1);border:1px solid #00ff88;color:#00ff88;padding:10px;border-radius:6px;margin-bottom:15px;}
        .aviso-error{background:rgba(255,38,65,0.1);border:1px solid #ff2641;color:#ff2641;padding:10px;border-radius:6px;margin-bottom:15px;}
        header{text-align:center;padding:15px;background:#0a0f0d;border-bottom:1px solid var(--verde-fantasma);}
        nav a{color:#fff;text-decoration:none;border:1px solid var(--rojo-peligro);padding:6px 12px;border-radius:6px;margin:4px;display:inline-block;}
        nav a.activo{background:var(--rojo-peligro);}
        .contenido{max-width:800px;margin:30px auto;background:#111616;padding:30px;border-radius:12px;border:1px solid #0f3d2e;}
        .grid-equipo,.grid-fantasmas,.galeria-grid{display:grid;gap:20px;}
        .grid-equipo{grid-template-columns:repeat(auto-fit,minmax(150px,1fr));}
        .grid-fantasmas{grid-template-columns:repeat(auto-fit,minmax(200px,1fr));}
        .tarjeta,.equipo-item,.galeria-item{background:#0a0f0d;border:1px solid rgba(77,255,158,0.2);padding:15px;border-radius:8px;text-align:center;}
        .icono-svg{max-width:120px;margin:0 auto;}
        footer{text-align:center;padding:20px;color:#888;font-size:0.8rem;}
    </style>
</head>
<body class="<?php echo $es_pantalla_caja ? 'pantalla-caja' : ''; ?>">

<?php if ($es_pantalla_caja): ?>
    <?php if ($vista === 'entrada'): ?>
        <div class="caja">
            <h1>Ghost Hunter's Den</h1>
            <p>Fan zone dedicada a Phasmophobia</p>
            <form method="POST">
                <input type="text" name="investigador" placeholder="Tu nombre de investigador" required>
                <button type="submit">Entrar</button>
            </form>
        </div>
    <?php elseif ($vista === 'bienvenida'): ?>
        <div class="caja">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['investigador']); ?></h1>
            <p>Prepárate para la cacería.</p>
            <a href="?vista=inicio" style="color:var(--verde-fantasma);">Continuar al sitio</a>
        </div>
    <?php endif; ?>
<?php else: ?>

<header>
    <div style="color:#ffd700; margin-bottom:10px;">> Fan zone dedicada a Phasmophobia <</div>
    <nav>
        <a href="?vista=inicio" class="<?php echo $vista==='inicio'?'activo':''; ?>">Inicio</a>
        <a href="?vista=sobre-el-juego" class="<?php echo $vista==='sobre-el-juego'?'activo':''; ?>">Sobre el Juego</a>
        <a href="?vista=fantasmas" class="<?php echo $vista==='fantasmas'?'activo':''; ?>">Tipos de Fantasmas</a>
        <a href="?vista=galeria" class="<?php echo $vista==='galeria'?'activo':''; ?>">Galería</a>
        <a href="?vista=contacto" class="<?php echo $vista==='contacto'?'activo':''; ?>">Contacto</a>
    </nav>
</header>

<div class="contenido">
    <?php if ($vista === 'inicio'): ?>
        <h2>¿Te atreves a entrar?</h2>
        <div class="hero-svg"><?php echo svgCasaEmbrujada(); ?></div>
        <p>Este sitio es un homenaje a <strong>Phasmophobia</strong>, el juego cooperativo de caza de fantasmas.</p>
    <?php elseif ($vista === 'sobre-el-juego'): ?>
        <h2>Sobre Phasmophobia</h2>
        <p>Phasmophobia es un juego de terror psicológico cooperativo desarrollado por Kinetic Games. Hasta cuatro jugadores forman un equipo de investigación paranormal.</p>
        <h2 style="margin-top:30px;">Equipo de Caza</h2>
        <div class="grid-equipo">
            <div class="equipo-item"><div class="icono-svg"><?php echo svgEquipo('emf'); ?></div><p>Detector EMF</p></div>
            <div class="equipo-item"><div class="icono-svg"><?php echo svgEquipo('camara'); ?></div><p>Cámara de espíritus</p></div>
            <div class="equipo-item"><div class="icono-svg"><?php echo svgEquipo('linterna'); ?></div><p>Linterna UV</p></div>
            <div class="equipo-item"><div class="icono-svg"><?php echo svgEquipo('termometro'); ?></div><p>Termómetro</p></div>
        </div>
    <?php elseif ($vista === 'fantasmas'): ?>
        <h2>Tipos de Fantasmas</h2>
        <div class="grid-fantasmas">
            <?php
            $fantasmas = [
                ["nombre" => "Spirit", "color" => "#4dff9e", "desc" => "El más común y equilibrado; vulnerable al humo de salvia.", "evidencias" => "EMF 5, Libro fantasma, Orbe"],
                ["nombre" => "Demon", "color" => "#ff2e4d", "desc" => "Inicia cacerías con más frecuencia.", "evidencias" => "EMF 5, Libro fantasma, Huella térmica"],
                ["nombre" => "Revenant", "color" => "#ffcf6b", "desc" => "Lento si no te ve, letal si te encuentra.", "evidencias" => "Libro fantasma, Orbe, Huella térmica"],
                ["nombre" => "Yurei", "color" => "#9bdcff", "desc" => "Reduce la cordura más rápido.", "evidencias" => "EMF 5, Orbe, Huella térmica"],
            ];
            foreach ($fantasmas as $f) {
                echo '<div class="tarjeta"><div class="icono-svg">' . svgFantasma($f['color']) . '</div><h3>' . htmlspecialchars($f['nombre']) . '</h3><p>' . htmlspecialchars($f['desc']) . '</p><p class="evidencias">Evidencias: ' . htmlspecialchars($f['evidencias']) . '</p></div>';
            }
            ?>
        </div>
    <?php elseif ($vista === 'galeria'): ?>
        <h2>Galería de Cacerías</h2>
        <div class="galeria-grid">
            <div class="galeria-item"><h3>Investigación nocturna</h3><div class="icono-svg"><?php echo svgHabitacion(); ?></div></div>
            <div class="galeria-item"><h3>Clip de una cacería</h3><video controls><source src="media/caceria.mp4" type="video/mp4"></video><p>Agrega tu archivo en media/caceria.mp4</p></div>
            <div class="galeria-item"><h3>Grabación EVP</h3><audio controls><source src="media/evp.mp3" type="audio/mpeg"></audio><p>Agrega tu archivo en media/evp.mp3</p></div>
        </div>
    <?php elseif ($vista === 'contacto'): ?>
        <h2>Contacto</h2>
        <p>¿Tienes una historia de caza paranormal o quieres recomendarme un fantasma para investigar? Escríbeme:</p>
        <?php echo $mensaje_db; ?>
        <form action="?vista=contacto" method="POST">
            <div><label for="nombre">Tu Nombre:</label><br><input type="text" id="nombre" name="nombre" required></div>
            <div><label for="correo">Correo Electrónico:</label><br><input type="email" id="correo" name="correo" required></div>
            <div><label for="fantasma_favorito">Tu Fantasma Favorito:</label><br><input type="text" id="fantasma_favorito" name="fantasma_favorito" required></div>
            <div><label for="mensaje">Tu Mensaje:</label><br><textarea id="mensaje" name="mensaje" rows="4" required></textarea></div>
            <button type="submit">Enviar Mensaje</button>
        </form>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Ghost Hunter's Den - Proyecto académico de Jimmy</p>
</footer>

<?php endif; ?>
</body>
</html>
