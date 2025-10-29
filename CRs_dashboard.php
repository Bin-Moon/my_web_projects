<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    header("Location: login.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>CR Dashboard</title>

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --accent: #2980b9;
      --muted: #f4f6f8;
      --panel: #fff;
      --sidebar: #2c3e50
    }

    * {
      box-sizing: border-box
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      background: var(--muted);
      color: #222
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background: var(--sidebar);
      color: #fff;
      position: fixed;
      display: flex;
      flex-direction: column
    }

    .sidebar h2 {
      margin: 0;
      padding: 15px 10px;
      background: #1a252f;
      text-align: center
    }

    .sidebar a {
      padding: 14px 16px;
      color: #fff;
      text-decoration: none;
      display: flex;
      gap: 10px;
      align-items: center
    }

    .sidebar a:hover {
      background: #34495e
    }

    .main {
      margin-left: 250px;
      padding: 20px
    }

    .title-box {
      background: var(--panel);
      padding: 14px 18px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
      margin-bottom: 16px
    }

    .academic-selection {
      display: flex;
      gap: 10px;
      align-items: center;
      background: var(--panel);
      padding: 12px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
      margin-bottom: 18px;
      flex-wrap: wrap
    }

    .academic-selection label {
      font-weight: 600
    }

    .academic-selection select,
    .academic-selection button {
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #ccc
    }

    .academic-selection .applied {
      margin-left: auto;
      color: #555;
      font-weight: 600
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 18px
    }

    .card {
      background: var(--panel);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
      text-align: center;
      cursor: pointer;
      transition: transform .12s, box-shadow .12s
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 26px rgba(0, 0, 0, 0.08)
    }

    .card i {
      font-size: 36px;
      color: var(--accent);
      display: block;
      margin-bottom: 10px
    }

    .card-title {
      font-weight: 700;
      margin-bottom: 6px
    }

    .card-sub {
      color: #666;
      font-size: 13px
    }

    /* modal */
    .modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .45);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 18px;
      z-index: 9999
    }

    .modal {
      width: 90%;
      max-width: 900px;
      background: var(--panel);
      border-radius: 10px;
      padding: 25px;
      max-height: 95vh;
      overflow: auto;
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
    }

    .modal .modal-head {
      display: flex;
      gap: 12px;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px
    }

    .modal h3 {
      margin: 0
    }

    .close-btn {
      background: #eee;
      padding: 6px 10px;
      border-radius: 6px;
      border: 0;
      cursor: pointer
    }

    form .row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 10px
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      font-size: 13px
    }

    input[type="text"],
    input[type="date"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #ccc
    }

    textarea {
      min-height: 200px
    }

    .form-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 12px
    }

    .btn {
      background: var(--accent);
      color: #fff;
      padding: 10px 14px;
      border-radius: 8px;
      border: 0;
      cursor: pointer
    }

    .btn.secondary {
      background: #666
    }

    @media (max-width:700px) {
      .sidebar {
        width: 72px
      }

      .main {
        margin-left: 72px
      }

      .academic-selection {
        flex-direction: column;
        align-items: stretch
      }
    }
  </style>
</head>

<body>

  <div class="sidebar">
    <h2>CR Panel</h2>
    <a href="#manage_students"><i class="fas fa-users"></i><span class="nav-text">Dashboard</span></a>
    <a href="#reports"><i class="fas fa-file-alt"></i><span class="nav-text">Reports</span></a>
    <a href="#announcements"><i class="fas fa-bullhorn"></i><span class="nav-text">Announcements</span></a>
    <a href="#tasks"><i class="fas fa-tasks"></i><span class="nav-text">Tasks</span></a>
    <a href="#chat"><i class="fas fa-comments"></i><span class="nav-text">Chat</span></a>
    <a href="#study_material"><i class="fas fa-book"></i><span class="nav-text">Study Material</span></a>
    <a href="#routine"><i class="fas fa-table"></i><span class="nav-text">Class Routine</span></a>
    <a href="#schedule"><i class="fas fa-clock"></i><span class="nav-text">Class Schedule</span></a>
    <a href="#exam_routine"><i class="fas fa-calendar-alt"></i><span class="nav-text">Exam Routine</span></a>
    <a href="#settings"><i class="fas fa-cog"></i><span class="nav-text">Settings</span></a>
  </div>


  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Handle sidebar links
      document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', (e) => {
          e.preventDefault(); // prevent scrolling to anchor
          const hash = link.getAttribute('href'); // e.g. "#attendance"
          if (hash) {
            const module = hash.substring(1); // remove '#' -> "attendance"
            openModuleModal(module);
          }
        });
      });

      // You can keep cards logic as it is
      document.getElementById('cardGrid').addEventListener('click', (e) => {
        const card = e.target.closest('.card');
        if (!card) return;
        const module = card.getAttribute('data-module');
        openModuleModal(module);
      });
    });
  </script>


  <div class="main">
    <div class="title-box">
      <h1>Class Representative Dashboard</h1>
    </div>

    <!-- global filters -->
    <div class="academic-selection">
      <div style="min-width:170px">
        <label for="year">Year</label>
        <select id="year">
          <option value="2020-2021">2020-2021</option>
          <option value="2021-2022">2021-2022</option>
          <option value="2022-2023">2022-2023</option>
          <option value="2023-2024" selected>2023-2024</option>
          <option value="2024-2025">2024-2025</option>
        </select>
      </div>

      <div style="min-width:170px">
        <label for="semester">Semester</label>
        <select id="semester">
          <option value="1">Semester 1</option>
          <option value="2">Semester 2</option>
          <option value="3">Semester 3</option>
          <option value="4">Semester 4</option>
          <option value="5" selected>Semester 5</option>
          <option value="6">Semester 6</option>
          <option value="7">Semester 7</option>
          <option value="8">Semester 8</option>
        </select>
      </div>

      <button id="applyBtn">Apply</button>
    </div>

    <!-- cards -->
    <div class="card-grid" id="cardGrid">
      <div class="card" data-module="manage_students"><i class="fas fa-users"></i>
        <div class="card-title">Manage Students</div>
        <div class="card-sub">View / edit / search students</div>
      </div>

      <div class="card" data-module="reports"><i class="fas fa-file-alt"></i>
        <div class="card-title">Reports</div>
        <div class="card-sub">Assign / collect lab reports</div>
      </div>

      <div class="card" data-module="announcements"><i class="fas fa-bullhorn"></i>
        <div class="card-title">Announcements</div>
        <div class="card-sub">Post a notice for selected batch</div>
      </div>

      <div class="card" data-module="tasks"><i class="fas fa-tasks"></i>
        <div class="card-title">Assign Tasks</div>
        <div class="card-sub">Create task & deadline</div>
      </div>

      <div class="card" data-module="chat"><i class="fas fa-comments"></i>
        <div class="card-title">Chat</div>
        <div class="card-sub">Message students</div>
      </div>

      <div class="card" data-module="study_material"><i class="fas fa-book"></i>
        <div class="card-title">Study Material</div>
        <div class="card-sub">Upload PDFs & links</div>
      </div>

      <div class="card" data-module="routine"><i class="fas fa-table"></i>
        <div class="card-title">Class Routine</div>
        <div class="card-sub">Upload / update class routine</div>
      </div>

      <div class="card" data-module="schedule"><i class="fas fa-clock"></i>
        <div class="card-title">Class Schedule</div>
        <div class="card-sub">Write schedule text</div>
      </div>

      <div class="card" data-module="exam_routine"><i class="fas fa-calendar-alt"></i>
        <div class="card-title">Exam Routine</div>
        <div class="card-sub">Upload exam timetable</div>
      </div>

      <div class="card" data-module="settings"><i class="fas fa-cog"></i>
        <div class="card-title">Settings</div>
        <div class="card-sub">Update profile / password</div>
      </div>
    </div>
  </div>

  <!-- modal -->
  <div id="modalBackdrop" class="modal-backdrop" role="dialog" aria-hidden="true">
    <div class="modal" role="document">
      <div class="modal-head">
        <h3 id="modalTitle">Module</h3>
        <div>
          <button class="close-btn" id="closeModal">Close</button>
        </div>
      </div>
      <div id="modalBody"></div>
    </div>
  </div>

  <script>
    // helpers
    function getGlobalFilter() {
      const year = document.getElementById('year').value;
      const semester = document.getElementById('semester').value;
      return {
        year,
        semester
      };
    }

    // Apply button
    document.getElementById('applyBtn').addEventListener('click', () => {
      const {
        year,
        semester
      } = getGlobalFilter();
      alert('Filter applied: ' + year + ' | ' + semester);
    });

    // modal
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalBody = document.getElementById('modalBody');
    const modalTitle = document.getElementById('modalTitle');
    document.getElementById('closeModal').addEventListener('click', () => closeModal());

    document.getElementById('cardGrid').addEventListener('click', e => {
      const card = e.target.closest('.card');
      if (!card) return;
      const module = card.getAttribute('data-module');
      openModuleModal(module);
    });

    function openModuleModal(moduleKey) {
      const {
        year,
        semester
      } = getGlobalFilter();
      modalTitle.textContent = humanName(moduleKey) + ` — ${year} • ${semester}`;
      modalBody.innerHTML = buildModuleForm(moduleKey, year, semester);
      modalBackdrop.style.display = 'flex';
    }

    function closeModal() {
      modalBackdrop.style.display = 'none';
      modalBody.innerHTML = '';
    }

    function humanName(key) {
      return key.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    }

    function escapeHtml(s) {
      return ('' + s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // build module forms
    function buildModuleForm(key, year, semester) {
      const hidden = `<input type="hidden" name="year" value="${escapeHtml(year)}">
                  <input type="hidden" name="semester" value="${escapeHtml(semester)}">`;

      switch (key) { 
        case 'manage_students':
          return `<a class="btn" href="manage_students.php">Open Manage Students</a>`;
        case 'attendance':
          return `<form action="attendance.php" method="POST" enctype="multipart/form-data">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Course code / name</label>
                    <input type="text" name="course" placeholder="e.g. ICE-101" required>
                  </div>
                  <div style="min-width:200px">
                    <label>Date</label>
                    <input type="date" name="date" required>
                  </div>
                </div>
                <div class="row">
                  <div style="flex:1">
                    <label>Upload attendance (CSV optional)</label>
                    <input type="file" name="attendance_file" accept=".csv,.pfg,.xls,.xlsv">
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Submit Attendance</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
        case 'reports':
          return `<form action="report_upload.php" method="POST" enctype="multipart/form-data">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Lab / Report Title</label>
                    <input type="text" name="title" required>
                  </div>
                  <div style="min-width:200px">
                    <label>Due date</label>
                    <input type="date" name="due_date">
                  </div>
                </div>
                <div class="row">
                  <div style="flex:1">
                    <label>Attach assignment file (pdf/doc)</label>
                    <input type="file" name="report_file" accept=".pdf,.doc,.docx">
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Assign Report</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
        case 'announcements':
  return `<form action="post_announcement.php" method="POST">
      ${hidden}
      <div class="row">
        <div style="flex:1">
          <label>Course</label>
          <input type="text" name="course" placeholder="e.g. ICE-101" required>
        </div>
      </div>
      <div class="row">
        <div style="flex:1">
          <label>Message</label>
          <textarea name="message" required></textarea>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn">Post Announcement</button>
        <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
      </div>
    </form>`;

        case 'tasks':
          return `<form action="create_task.php" method="POST">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Task Title</label>
                    <input type="text" name="title" required>
                  </div>
                  <div style="min-width:180px">
                    <label>Due Date</label>
                    <input type="date" name="due_date">
                  </div>
                </div>
                <div class="row">
                  <div style="flex:1">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Create Task</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
        case 'chat':
          return `<form action="cr_chat.php" method="POST">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Message</label>
                    <input type="text" name="message" >
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Send</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
        case 'study_material':
          return `<form action="upload_material.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="year" value="2023-2024">
    <input type="hidden" name="semester" value="5">

    <div class="row">
        <div style="flex:1">
            <label>Course Code</label>
            <input type="text" name="course" placeholder="e.g. ICE-101" required>
        </div>
        <div style="flex:1">
            <label>Material Title</label>
            <input type="text" name="title" required>
        </div>
        <div style="flex:1">
            <label>File</label>
            <input type="file" name="material_file" required>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Upload Material</button>
        <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
    </div>
</form>`;
        case 'routine':
          return `<form action="upload_routine.php" method="POST" enctype="multipart/form-data">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Routine File (PDF)</label>
                   <input type="file" name="routine_file" accept=".pdf,.jpg,.jpeg,.png" required>

                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Upload Routine</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
        case 'schedule':
          return `<form action="upload_schedule.php" method="POST" id="scheduleText">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Write Class Schedule</label>
                    <textarea name="schedule_text" placeholder="Enter class schedule here..." required></textarea>
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Save Schedule</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
          

        case 'exam_routine':
          return `<form action="upload_exam.php" method="POST" enctype="multipart/form-data">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Exam Routine File</label>
                   <input type="file" name="exam_file" accept=".pdf,.jpg,.jpeg,.png" required>

                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Upload Exam Routine</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
        case 'settings':
          return `<form action=" update_settings.php" method="POST">
                ${hidden}
                <div class="row">
                  <div style="flex:1">
                    <label>Change Password</label>
                    <input type="text" name="new_password" placeholder="New password" required>
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn">Update Settings</button>
                  <button class="btn secondary" type="button" onclick="closeModal()">Cancel</button>
                </div>
              </form>`;
        default:
          return `<p>Module not implemented yet.</p>`;
      }
    }
  </script>

</body>

</html>