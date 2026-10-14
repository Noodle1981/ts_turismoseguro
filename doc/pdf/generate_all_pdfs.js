const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';

const filesToGenerate = [
  {
    name: 'Business Model Canvas',
    html: path.resolve(__dirname, 'business_model_canvas.html'),
    pdf: path.resolve(__dirname, 'business_model_canvas.pdf'),
    copyTo: path.resolve(__dirname, '..', 'business_model_canvas.pdf'),
  },
  {
    name: 'FODA y Estrategias CAME',
    html: path.resolve(__dirname, 'foda.html'),
    pdf: path.resolve(__dirname, 'foda.pdf'),
    copyTo: path.resolve(__dirname, '..', 'foda.pdf'),
  },
];

console.log('--- Iniciando generación de PDFs de alta definición (Modo Ahorro) ---');

for (const item of filesToGenerate) {
  console.log(`\nGenerando: ${item.name}...`);
  console.log(`  HTML: ${item.html}`);
  console.log(`  PDF:  ${item.pdf}`);

  const cmd = `"${chromePath}" --headless=new --disable-gpu --no-pdf-header-footer --print-to-pdf="${item.pdf}" "${item.html}"`;
  
  try {
    execSync(cmd, { encoding: 'utf-8' });
    if (fs.existsSync(item.pdf)) {
      const stats = fs.statSync(item.pdf);
      console.log(`  ✓ PDF creado con éxito: ${(stats.size / 1024).toFixed(1)} KB`);
      // Copiar también a doc/ para acceso directo
      fs.copyFileSync(item.pdf, item.copyTo);
      console.log(`  ✓ Copiado a: ${item.copyTo}`);
    } else {
      console.error(`  ✗ Error: El archivo no fue creado.`);
    }
  } catch (err) {
    console.error(`  ✗ Falló la ejecución para ${item.name}:`, err.message);
  }
}

console.log('\n--- Generación completada con éxito ---');
