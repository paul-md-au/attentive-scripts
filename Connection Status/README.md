# Global Config - Connection Status Check

## Database Schema

**Database:** `mdattentivesmscomposer`  
**Table:** `attentive_oauth_tokens`

```json
{
  "id": 1,
  "client_id": "****",
  "access_token": "****",
  "refresh_token": "****",
  "token_type": "Bearer",
  "expires_in": 3600,
  "scope": "all",
  "status": "active",
  "created_at": "2026-03-09 04:18:08",
  "updated_at": "2026-03-09 04:18:08"
}
```

---

## Source Code

📎 [connection-status.php](./connection-status.php)

---

## Response Examples

### Test 1: Active Connection ✅

**Response:**
```json
{
  "success": true,
  "status": "connected",
  "last_verified": "2026-03-09 04:18:08"
}
```

---

### Test 2: Status is Not Active ✅

**Response:**
```json
{
  "success": true,
  "status": "disconnected",
  "last_verified": "2026-03-12 04:53:07",
  "message": "Status is not active"
}
```

---

### Test 3: Client ID Not Found ✅

**Response:**
```json
{
  "success": true,
  "status": "disconnected",
  "last_verified": null,
  "message": "Client ID not found"
}
```

---

### Test 4: Database Connection Failed ✅

**Response:**
```json
{
  "success": false,
  "message": "Database error"
}
```