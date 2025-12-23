# API Documentation - Sistem Absensi RFID

## Base URL
```
http://localhost/absen-rfid-new/
```

## Authentication
Most endpoints require authentication via session. The RFID endpoints are public and don't require login.

---

## RFID Endpoints

### 1. RFID Scan Page
**Endpoint:** `GET /rfid` or `GET /rfid/scan`  
**Authentication:** None (Public)  
**Description:** Display public RFID scanning page

**Response:** HTML page

---

### 2. Process RFID Tap
**Endpoint:** `POST /rfid/scan/process`  
**Authentication:** None (Public)  
**Description:** Process RFID card tap for attendance

**Request Body:**
```json
{
  "uid": "RFID-SISWA-001"
}
```

**Success Response (Tap Masuk):**
```json
{
  "status": "success",
  "message": "Absensi masuk berhasil",
  "data": {
    "nama": "Andi Pratama",
    "kelas_jabatan": "X RPL 1",
    "foto": "photo.jpg",
    "jam_masuk": "07:05:00",
    "status": "Hadir",
    "keterlambatan": 5,
    "type": "masuk",
    "user_type": "siswa"
  },
  "sound": "success"
}
```

**Success Response (Tap Pulang):**
```json
{
  "status": "success",
  "message": "Absensi pulang berhasil",
  "data": {
    "nama": "Andi Pratama",
    "kelas_jabatan": "X RPL 1",
    "foto": "photo.jpg",
    "jam_pulang": "15:10:00",
    "type": "pulang",
    "user_type": "siswa"
  },
  "sound": "success"
}
```

**Error Response (Card Not Found):**
```json
{
  "status": "error",
  "message": "Kartu tidak terdaftar",
  "sound": "error"
}
```

**Info Response (Already Tapped):**
```json
{
  "status": "info",
  "message": "Anda sudah melakukan absensi masuk dan pulang hari ini",
  "data": {
    "nama": "Andi Pratama",
    "kelas_jabatan": "X RPL 1",
    "foto": "photo.jpg",
    "jam_masuk": "07:05:00",
    "jam_pulang": "15:10:00",
    "user_type": "siswa"
  },
  "sound": "info"
}
```

---

### 3. Get Today's Attendance
**Endpoint:** `GET /rfid/scan/get_today`  
**Authentication:** None (Public)  
**Description:** Get all attendance records for today (for real-time display)

**Success Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "tanggal": "2024-12-23",
      "user_type": "siswa",
      "user_id": 1,
      "uid_rfid": "RFID-SISWA-001",
      "nama": "Andi Pratama",
      "foto": "photo.jpg",
      "kelas_jabatan": "X RPL 1",
      "jam_masuk": "07:05:00",
      "jam_pulang": null,
      "status_masuk": "Hadir",
      "keterlambatan": 5,
      "created_at": "2024-12-23 07:05:12"
    },
    {
      "id": 2,
      "tanggal": "2024-12-23",
      "user_type": "guru",
      "user_id": 1,
      "uid_rfid": "RFID-GURU-001",
      "nama": "Ahmad Fauzi, S.Pd",
      "foto": null,
      "kelas_jabatan": "Guru Mapel",
      "jam_masuk": "06:55:00",
      "jam_pulang": null,
      "status_masuk": "Hadir",
      "keterlambatan": 0,
      "created_at": "2024-12-23 06:55:30"
    }
  ]
}
```

---

## Authentication Endpoints

### 1. Login Page
**Endpoint:** `GET /login` or `GET /auth/login`  
**Authentication:** None  
**Description:** Display login page

**Response:** HTML page

---

### 2. Process Login
**Endpoint:** `POST /auth/do_login`  
**Authentication:** None  
**Description:** Authenticate user and create session

**Request Body:**
```
username: admin
password: password
```

**Success:** Redirect to role-specific dashboard  
**Error:** Redirect back to login with error message

---

### 3. Logout
**Endpoint:** `GET /auth/logout`  
**Authentication:** Required  
**Description:** Destroy session and logout user

**Success:** Redirect to login page

---

## Dashboard Endpoints

### 1. Admin Dashboard
**Endpoint:** `GET /admin/dashboard`  
**Authentication:** Required (Role: admin)  
**Description:** Display admin dashboard with statistics

**Response:** HTML page with:
- Total students and teachers
- Today's attendance count
- Attendance chart (7 days)
- Recent attendance list

---

## Integration with RFID Hardware

### Hardware Requirements
- RFID RC522 Reader Module
- Arduino/ESP32/ESP8266 Microcontroller
- WiFi connection

### Example Arduino Code
```cpp
#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

#define SS_PIN D8
#define RST_PIN D0

MFRC522 mfrc522(SS_PIN, RST_PIN);

const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverUrl = "http://your-server.com/rfid/scan/process";

void setup() {
  Serial.begin(115200);
  SPI.begin();
  mfrc522.PCD_Init();
  
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("WiFi connected");
}

void loop() {
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    delay(50);
    return;
  }
  
  // Read UID
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();
  
  // Send to server
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    WiFiClient client;
    
    http.begin(client, serverUrl);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    String postData = "uid=" + uid;
    int httpResponseCode = http.POST(postData);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println(response);
    }
    
    http.end();
  }
  
  delay(2000); // Prevent multiple reads
}
```

---

## Webhook/Cron Jobs

### 1. Process WhatsApp Queue
**Endpoint:** `GET /cron/process_wa_queue`  
**Authentication:** None (Should be restricted to server IP)  
**Description:** Process pending WhatsApp notifications from queue

**Cron Schedule:** Every 1 minute
```
* * * * * php /path/to/absen-rfid-new/index.php cron/process_wa_queue
```

---

### 2. Auto Notification for Absent Students
**Endpoint:** `GET /cron/notif_siswa_alpha`  
**Authentication:** None (Should be restricted to server IP)  
**Description:** Send notification to homeroom teachers about absent students

**Cron Schedule:** Daily at 09:00 AM
```
0 9 * * * php /path/to/absen-rfid-new/index.php cron/notif_siswa_alpha
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 200  | Success |
| 400  | Bad Request (Invalid parameters) |
| 401  | Unauthorized (Not logged in) |
| 403  | Forbidden (Insufficient permissions) |
| 404  | Not Found |
| 500  | Internal Server Error |

---

## Rate Limiting

Currently, there is no rate limiting implemented. For production use, consider implementing rate limiting on RFID endpoints to prevent abuse.

---

## Testing

### Testing RFID Endpoint with cURL

```bash
# Test with valid UID
curl -X POST http://localhost/absen-rfid-new/rfid/scan/process \
  -d "uid=RFID-SISWA-001"

# Test with invalid UID
curl -X POST http://localhost/absen-rfid-new/rfid/scan/process \
  -d "uid=INVALID-UID"
```

### Testing with Postman

1. Create a new POST request
2. URL: `http://localhost/absen-rfid-new/rfid/scan/process`
3. Body (x-www-form-urlencoded):
   - Key: `uid`
   - Value: `RFID-SISWA-001`
4. Send request

---

## Demo Accounts

| Username | Password | Role |
|----------|----------|------|
| admin | password | Administrator |
| guru1 | password | Guru |
| guru2 | password | Wali Kelas |
| piket1 | password | Guru Piket |
| bk1 | password | Guru BK |

## Demo RFID Cards

| UID | Type | Name |
|-----|------|------|
| RFID-SISWA-001 | Siswa | Andi Pratama |
| RFID-SISWA-002 | Siswa | Bella Angelina |
| RFID-SISWA-003 | Siswa | Citra Dewi |
| RFID-GURU-001 | Guru | Ahmad Fauzi, S.Pd |
| RFID-GURU-002 | Guru | Siti Nurhaliza, S.Pd |

---

**Last Updated:** December 23, 2024  
**Version:** 1.0.0
