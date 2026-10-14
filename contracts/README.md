# 📜 Contratos de API (Single Source of Truth)

Este directorio alberga la especificación canónica **OpenAPI 3.1** de la API para `Turismo Seguro`.

---

## 📖 Cómo visualizar la documentación interactivamente con Scalar

No necesitás levantar Laravel ni una base de datos para explorar esta API.

### Visor Rápido en Vivo con Scalar (Recomendado)
```bash
npm run docs
```
Abrí `http://localhost:5054` en tu navegador para ver la interfaz interactiva moderna de Scalar con recarga en tiempo real (*hot reload*) al modificar `contracts/openapi.yaml`.

---

## ⚡ Generación Automática de Tipos TypeScript para Vue 3

Para mantener sincronizado el frontend sin tipar a mano:
```bash
npm run api:types
```
Genera automáticamente las interfaces en `client/src/types/schema.d.ts` directamente desde `contracts/openapi.yaml`.

---

## 🔄 Flujo de Trabajo Contract-First

1. Editar `contracts/openapi.yaml` agregando los endpoints y schemas correspondientes.
2. Ejecutar `npm run api:types` para actualizar los tipos en el frontend (`client/`).
3. Implementar en Laravel (`server/`) el Form Request, Controller y API Resource reflejando fielmente el contrato.
4. Conectar en Vue 3 consumiendo los tipos generados.
