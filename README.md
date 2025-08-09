
# GH-timeline

A PHP-based email verification and GitHub timeline update system. Users can subscribe with email, verify using a one-time code, and receive periodic updates of GitHub activity. A CRON job sends out updates every 5 minutes. Unsubscribe functionality is also included.

## 📌 Features

### ✅ 1. Email Verification
- Users register their email through a form.
- A 6-digit verification code is emailed to them.
- Once verified, the email is stored in `registered_emails.txt`.

### ✅ 2. GitHub Timeline Email Updates
- A CRON job runs every 5 minutes.
- It fetches data from `https://www.github.com/timeline`.
- The data is formatted into HTML and sent to all verified emails.
- Each email includes an **unsubscribe link**.

### ✅ 3. Unsubscribe Mechanism
- Clicking the unsubscribe link opens a form.
- A 6-digit code is emailed for confirmation.
- Upon code verification, the email is removed from the subscription list.

## 🧩 Folder Structure

```
src/
├── index.php                 # Main form for email verification
├── functions.php             # All core functionality
├── unsubscribe.php           # Unsubscribe confirmation page
├── cron.php                  # CRON job to send GitHub timeline updates
├── setup_cron.sh             # Script to set up CRON job
├── style.css                 # Basic styling
└── registered_emails.txt     # Stores verified emails
```

## 📄 Requirements

- PHP ≥ 8.3
- No external libraries (pure PHP only)
- MailHog or similar for local email testing
- CRON support for scheduled jobs

## ⚙️ Setup Instructions

1. Clone the repo and create a new branch:
   ```bash
   git clone https://github.com/YOUR_USERNAME/GH-timeline.git
   cd GH-timeline
   git checkout -b your-branch-name
   ```

2. Run the CRON setup script:
   ```bash
   cd src
   chmod +x setup_cron.sh
   ./setup_cron.sh
   ```

3. Start MailHog (if used) and a local PHP server:
   ```bash
   php -S localhost:8000 -t src
   ```

## 📬 Email Formats

### ✅ Verification Email
- **Subject:** Your Verification Code  
- **Body:**  
  ```html
  <p>Your verification code is: <strong>123456</strong></p>
  ```

### ✅ GitHub Updates Email
- **Subject:** Latest GitHub Updates  
- **Body:**  
  ```html
  <h2>GitHub Timeline Updates</h2>
  <table border='1'>
    <tr><th>Event</th><th>User</th></tr>
    <tr><td>PushEvent</td><td>octocat</td></tr>
  </table>
  <p><a href="unsubscribe_url" id="unsubscribe-button">Unsubscribe</a></p>
  ```

### ✅ Unsubscribe Confirmation
- **Subject:** Confirm Unsubscription  
- **Body:**  
  ```html
  <p>To confirm unsubscription, use this code: <strong>654321</strong></p>
  ```

## 🛡️ Submission Rules (Followed)

- All code written in the `src/` directory only ✅  
- No third-party libraries used ✅  
- Emails not hardcoded; stored in `registered_emails.txt` ✅  
- CRON job implemented via `setup_cron.sh` ✅  
- Form fields always visible ✅  
- GitHub data formatted in HTML ✅

## 🧠 Author

**Abhiraj Singh Chouhan**  
B.Tech Computer Science (Specialization: Data Science)  
Email: cocabhiraj123@gmail.com 
GitHub: https://github.com/ajokbyy
