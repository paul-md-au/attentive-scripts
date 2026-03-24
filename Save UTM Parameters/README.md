# Global Config - Save UTM Parameters

## Database Schema

**Database:** `mdattentivesmscomposer`  
**Table:** `global_config`

```json
{
  "id": 1,
  "elq_sitename": "Paciolan",
  "att_workspace": "paciolan-marketing",
  "elq_field_attentiveid": {
    "id": "C_EmailAddress",
    "name": "Attentive Subscriber ID"
  },
  "elq_field_smsoptin": {
    "id": "C_SMS_OptIn1",
    "name": "SMS Opt-In Status"
  },
  "elq_smsoptin_values": {
    "opted_in": "Y",
    "opted_out": "N"
  },
  "create_trans_subscribers": true,
  "create_mark_subscribers": true,
  "phone_format": "+1",
  "elq_cdo_id": 805,
  "elq_cdo_fields": {
    "email_address": "CDO-806-F1",
    "mobile_number": "CDO-806-F2",
    "time_stamp": "CDO-806-F3",
    "source": "CDO-806-F4",
    "direction": "CDO-806-F5",
    "content": "CDO-806-F6",
    "campaign_tags": "CDO-806-F7",
    "campaign_name": "CDO-806-F8",
    "campaign_id": "CDO-806-F9",
    "message_name": "CDO-806-F10",
    "message_id": "CDO-806-F11"
  },
  "status": "complete",
  "created_date": "2024-03-01",
  "updated_date": "2026-03-24 04:40:26",
  "utm_params": {
    "utm_source": "eloqua",
    "utm_medium": "sms"
  },
  "link_short_url": "go.paciolan.com"
}
```

---

## Source Code

📎 [save-utm.php](./save-utm.php)

---

## Validation Rules

| Parameter | Required | Max Length | Allowed Characters |
|-----------|----------|------------|-------------------|
| `utm_source` | ✅ Yes | 100 | `a-z`, `A-Z`, `0-9`, `_`, `-`, space |
| `utm_medium` | ✅ Yes | 100 | `a-z`, `A-Z`, `0-9`, `_`, `-`, space |
| `utm_campaign` | ❌ No | 100 | `a-z`, `A-Z`, `0-9`, `_`, `-`, space |
| `utm_term` | ❌ No | 100 | `a-z`, `A-Z`, `0-9`, `_`, `-`, space |
| `utm_content` | ❌ No | 100 | `a-z`, `A-Z`, `0-9`, `_`, `-`, space |

---

## Postman Tests

### Test 1: Valid Input (All Parameters) ✅

**Request:**
```json
{
  "utm_source": "eloqua",
  "utm_medium": "sms",
  "utm_campaign": "spring_2024",
  "utm_term": "promo",
  "utm_content": "variant_a"
}
```

**Response:**
```json
{
  "success": true,
  "message": "UTM parameters saved successfully",
  "data": {
    "utm_source": "eloqua",
    "utm_medium": "sms",
    "utm_campaign": "spring_2024",
    "utm_term": "promo",
    "utm_content": "variant_a"
  }
}
```

---

### Test 2: Valid Input (Minimum Required) ✅

**Request:**
```json
{
  "utm_source": "eloqua",
  "utm_medium": "sms"
}
```

**Response:**
```json
{
  "success": true,
  "message": "UTM parameters saved successfully",
  "data": {
    "utm_source": "eloqua",
    "utm_medium": "sms"
  }
}
```

---

### Test 3: Missing Required Field ✅

**Request:**
```json
{
  "utm_campaign": "spring_2024"
}
```

**Response:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "utm_source": "Required field",
    "utm_medium": "Required field"
  }
}
```

---

### Test 4: Invalid Characters ✅

**Request:**
```json
{
  "utm_source": "eloqua",
  "utm_medium": "sms",
  "utm_campaign": "50% off!"
}
```

**Response:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "utm_campaign": "Invalid characters (use only a-z, 0-9, _, -, space)"
  }
}
```

---

### Test 5: Exceeds Maximum Length ✅

**Request:**
```json
{
  "utm_source": "eloqua",
  "utm_medium": "sms",
  "utm_campaign": "this_is_a_very_long_campaign_name_that_exceeds_one_hundred_characters_and_should_fail_validation_test"
}
```

**Response:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "utm_campaign": "Maximum 100 characters"
  }
}
```

---

### Test 6: Invalid Parameter ✅

**Request:**
```json
{
  "utm_source": "eloqua",
  "utm_medium": "sms",
  "custom_param": "hack"
}
```

**Response:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "custom_param": "Invalid UTM parameter"
  }
}
```
