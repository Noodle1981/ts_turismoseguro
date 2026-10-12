const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const htmlPath = path.resolve(__dirname, 'modelo_de_negocio.html');
const pdfPath = path.resolve(__dirname, 'modelo_de_negocio.pdf');

console.log('HTML path:', htmlPath);
console.log('PDF target:', pdfPath);

const cmd = `"${chromePath}" --headless=new --disable-gpu --no-pdf-header-footer --print-to-pdf="${pdfPath}" "${htmlPath}"`;
console.log('Executing:', cmd);

try {
  const result = execSync(cmd, { encoding: 'utf-8' });
  console.log('Result output:', result);
  if (fs.existsSync(pdfPath)) {
    const stats = fs.statSync(pdfPath);
    console.log(`PDF successfully created! Size: ${stats.size} bytes (${(stats.size / 1024).toFixed(1)} KB)`);
  } else {
    console.error('File was not created.');
  }
} catch (err) {
  console.error('Execution failed:', err.message);
}
