// // managebookings.js - Only bookings-specific functionality

// /* CONFIG */
// const FETCH_URL = '/api/bookings';
// const ROWS_PER_PAGE = 10;

// /* Extended fallback mock data */
// const fallbackBookings = [
//   { id: 1, activity: "Everest Helicopter Ride", date: "2025/10/10", time: "10:30 PM", user: "Rihana", slots: 5 },
//   { id: 2, activity: "Everest Helicopter Ride", date: "2025/10/10", time: "10:30 PM", user: "Eminem", slots: 1 },
//   { id: 3, activity: "Go Karting", date: "2025/11/10", time: "12:00 PM", user: "John", slots: 1 },
//   { id: 4, activity: "Paragliding", date: "2025/09/15", time: "02:00 PM", user: "Sarah", slots: 2 },
//   { id: 5, activity: "Bungee Jumping", date: "2025/12/20", time: "11:00 AM", user: "Mike", slots: 1 },
//   { id: 6, activity: "White Water Rafting", date: "2025/08/05", time: "09:30 AM", user: "Emma", slots: 4 },
//   { id: 7, activity: "Mountain Biking", date: "2025/07/12", time: "03:45 PM", user: "David", slots: 1 },
//   { id: 8, activity: "Rock Climbing", date: "2025/06/18", time: "01:15 PM", user: "Lisa", slots: 2 },
//   { id: 9, activity: "Zip Lining", date: "2025/11/25", time: "10:00 AM", user: "Alex", slots: 3 },
//   { id: 10, activity: "Hot Air Balloon", date: "2025/10/30", time: "06:00 AM", user: "Sophia", slots: 2 },
//   { id: 11, activity: "Scuba Diving", date: "2025/09/08", time: "08:30 AM", user: "James", slots: 1 },
//   { id: 12, activity: "Safari Tour", date: "2025/12/12", time: "07:00 AM", user: "Olivia", slots: 6 },
//   { id: 13, activity: "Skydiving Adventure", date: "2025/11/18", time: "01:00 PM", user: "Robert", slots: 2 },
//   { id: 14, activity: "Wine Tasting Tour", date: "2025/10/22", time: "03:30 PM", user: "Jennifer", slots: 4 },
//   { id: 15, activity: "Cooking Class", date: "2025/09/30", time: "11:00 AM", user: "William", slots: 1 },
//   { id: 16, activity: "Horseback Riding", date: "2025/08/14", time: "10:00 AM", user: "Amanda", slots: 2 },
// ];

// /* state for pagination */
// let bookings = [];
// let currentPage = 1;

// /* helper: fetch bookings from backend with fallback */
// async function loadBookingsFromServer() {
//   try {
//     const res = await fetch(FETCH_URL, { cache: "no-store" });
//     if (!res.ok) throw new Error('Network response not OK');
//     const data = await res.json();
//     if (!Array.isArray(data)) throw new Error('Invalid data format from server');
//     return data;
//   } catch (err) {
//     console.warn('Fetch failed, using fallback data:', err);
//     return fallbackBookings;
//   }
// }

// /* render bookings for current page */
// function renderTable() {
//   const tbody = document.querySelector('.table-body-rows');
//   tbody.innerHTML = '';

//   const totalBookings = bookings.length;
//   let pageData = [];

//   if (totalBookings > ROWS_PER_PAGE) {
//     // More than 10 records - use pagination
//     const start = (currentPage - 1) * ROWS_PER_PAGE;
//     pageData = bookings.slice(start, start + ROWS_PER_PAGE);
//   } else {
//     // 10 or fewer records - show all records
//     pageData = bookings;
//   }

//   if (pageData.length === 0) {
//     const row = document.createElement('div');
//     row.className = 'body-row empty-state';
//     row.innerHTML = '<div class="body-cell" style="width: 100%; justify-content: center; display: flex; align-items: center; height: 55px; grid-column: 1 / -1;">No bookings found</div>';
//     tbody.appendChild(row);
//     return;
//   }

//   pageData.forEach((b, idx) => {
//     const row = document.createElement('div');
//     row.className = 'body-row';
    
//     // Calculate index based on whether we're paginating or not
//     const globalIndex = totalBookings > ROWS_PER_PAGE 
//       ? (currentPage - 1) * ROWS_PER_PAGE + idx + 1 
//       : idx + 1;
    
//     row.innerHTML = `
//       <div class="body-cell sn">${globalIndex}</div>
//       <div class="body-cell activity">${escapeHtml(b.activity || '')}</div>
//       <div class="body-cell date">${escapeHtml(b.date || '')}</div>
//       <div class="body-cell time">${escapeHtml(b.time || '')}</div>
//       <div class="body-cell bookedby">${escapeHtml(b.user || '')}</div>
//       <div class="body-cell slots">${escapeHtml(String(b.slots || ''))}</div>
//     `;
//     tbody.appendChild(row);
//   });

//   // Update pagination display and visibility
//   updatePaginationDisplay();
// }

// /* Update pagination display and visibility */
// function updatePaginationDisplay() {
//   const pagination = document.getElementById('pagination');
//   const currentPageElement = document.getElementById('currentPage');
//   const prevBtn = document.getElementById('prevPage');
//   const nextBtn = document.getElementById('nextPage');
  
//   const totalBookings = bookings.length;
  
//   if (totalBookings > ROWS_PER_PAGE) {
//     // More than 10 records - show normal pagination with page numbers
//     currentPageElement.textContent = currentPage;
//     currentPageElement.style.fontStyle = 'normal';
//     currentPageElement.style.fontSize = '20px';
//     currentPageElement.style.color = '#2B2D42';
    
//     // Show arrow buttons
//     prevBtn.style.display = 'flex';
//     nextBtn.style.display = 'flex';
    
//     // Keep pagination container visible
//     pagination.style.display = 'flex';
//     pagination.style.justifyContent = 'flex-end';
    
//     // Update button states
//     prevBtn.disabled = (currentPage === 1);
//     nextBtn.disabled = (currentPage * ROWS_PER_PAGE >= totalBookings);
    
//     // Ensure buttons maintain their background color even when disabled
//     if (prevBtn.disabled) {
//       prevBtn.style.opacity = '0.5';
//       prevBtn.style.cursor = 'not-allowed';
//     } else {
//       prevBtn.style.opacity = '1';
//       prevBtn.style.cursor = 'pointer';
//     }
    
//     if (nextBtn.disabled) {
//       nextBtn.style.opacity = '0.5';
//       nextBtn.style.cursor = 'not-allowed';
//     } else {
//       nextBtn.style.opacity = '1';
//       nextBtn.style.cursor = 'pointer';
//     }
//   } else {
//     // 10 or fewer records - hide entire pagination
//     pagination.style.display = 'none';
//   }
// }

// /* simple HTML-escape to avoid injection */
// function escapeHtml(str){
//   return String(str)
//     .replace(/&/g,'&amp;')
//     .replace(/</g,'&lt;')
//     .replace(/>/g,'&gt;')
//     .replace(/"/g,'&quot;')
//     .replace(/'/g,'&#39;');
// }

// /* pagination handlers */
// function setupPaginationHandlers() {
//   document.getElementById('prevPage').addEventListener('click', () => {
//     const totalBookings = bookings.length;
//     if (currentPage > 1 && totalBookings > ROWS_PER_PAGE) { 
//       currentPage--; 
//       renderTable(); 
//     }
//   });
  
//   document.getElementById('nextPage').addEventListener('click', () => {
//     const totalBookings = bookings.length;
//     if (currentPage * ROWS_PER_PAGE < totalBookings && totalBookings > ROWS_PER_PAGE) { 
//       currentPage++; 
//       renderTable(); 
//     }
//   });
// }

// /* initial load */
// async function init() {
//   try {
//     bookings = await loadBookingsFromServer();
//     currentPage = 1;
//     renderTable();
//     setupPaginationHandlers();
//   } catch (error) {
//     console.error('Error initializing bookings:', error);
//     // Show error message to user
//     const tbody = document.querySelector('.table-body-rows');
//     tbody.innerHTML = '<div class="body-row error-state"><div class="body-cell" style="width: 100%; justify-content: center; padding: 20px; display: flex; align-items: center;">Error loading bookings. Please try again later.</div></div>';
    
//     // Hide pagination on error
//     document.getElementById('pagination').style.display = 'none';
//   }
// }

// /* Initialize when DOM is loaded */
// document.addEventListener('DOMContentLoaded', () => {
//   // init table
//   init();
// });
