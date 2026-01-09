const rooms = window.rooms;
const select = document.getElementById("room");

//show calendar for rooms
function showCalendar(roomId) {
  document.querySelectorAll(".calendars").forEach((cal) => {
    cal.style.display = cal.dataset.roomId == roomId ? "block" : "none";
  });
}

select.addEventListener("change", function () {
  const room = rooms.find((r) => r.id == this.value);

  // Hide preview
  if (!room) {
    document.getElementById("roomPreview").style.display = "none";
    showCalendar(null);
    return;
  }

  // Update preview
  document.getElementById("outImage").src =
    window.baseUrl + "/assets/images/" + room.outImage;
  document.getElementById("inImage").src =
    window.baseUrl + "/assets/images/" + room.inImage;

  document.getElementById("roomName").textContent = room.name;
  document.getElementById("roomPrice").textContent = room.cost + " g / night";
  document.getElementById("roomDescription").textContent = room.description;
  document.getElementById("roomPreview").style.display = "block";

  // calendar
  showCalendar(room.id);
});

// transfercode service
const transferForm = document.getElementById("getTransferCode");
const result = document.getElementById("transferResult");

transferForm.addEventListener("submit", function (event) {
  event.preventDefault();

  const formData = new FormData(transferForm);
  const data = Object.fromEntries(formData.entries());

  fetch("https://www.yrgopelag.se/centralbank/withdraw", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      user: data.user,
      api_key: data.api_key,
      amount: data.amount,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.error) {
        result.textContent = "Error: " + data.error;
      } else {
        result.textContent = "Your transfer code: " + data.transferCode;

        // autofill
        document.getElementById("transferCode").value = data.transferCode;
      }
    })
    .catch(() => {
      result.textContent = "Something went wrong. Try again.";
    });
});

// visa kostnad
const featureCheckboxes = document.querySelectorAll('input[name="features[]"]');
const totalEl = document.getElementById("totalPrice");

const checkInInput = document.getElementById("checkIn");
const checkOutInput = document.getElementById("checkOut");

function getNights() {
  if (!checkInInput.value || !checkOutInput.value) return 0;

  const checkIn = new Date(checkInInput.value);
  const checkOut = new Date(checkOutInput.value);

  const diffTime = checkOut - checkIn;
  const diffDays = diffTime / (1000 * 60 * 60 * 24);

  return diffDays > 0 ? diffDays : 0;
}
let userStays = 0;
function updateTotal() {
  let total = 0;
  const nights = getNights();

  // room cost × nights
  const selectedRoom = select.options[select.selectedIndex];
  if (selectedRoom && selectedRoom.dataset.cost && nights > 0) {
    total += Number(selectedRoom.dataset.cost) * nights;
  }

  // features
  featureCheckboxes.forEach((cb) => {
    if (cb.checked) {
      total += Number(cb.dataset.cost);
    }
  });

  // Loyalty discount
  let discount = 0;
  if (userStays > 0) {
    discount = 1;
    total -= discount;
  }

  // Update display
  totalEl.innerHTML = `<strong>Your total:</strong> ${total} g`;
  if (discount > 0) {
    totalEl.innerHTML += ` <span style="color:green;">(-${discount}g loyalty discount)</span>`;
  }
}

// Event listeners for total update
select.addEventListener("change", updateTotal);
featureCheckboxes.forEach((cb) => cb.addEventListener("change", updateTotal));
checkInInput.addEventListener("change", updateTotal);
checkOutInput.addEventListener("change", updateTotal);

//autoselect first room
window.addEventListener("DOMContentLoaded", () => {
  if (select.options.length > 1) {
    select.selectedIndex = 1;
    select.dispatchEvent(new Event("change"));
    updateTotal();
  }
});

// check loyalty
const checkLoyaltyButton = document.getElementById("checkLoyaltyButton");
const loyaltyMessage = document.getElementById("loyaltyMessage");

checkLoyaltyButton.addEventListener("click", () => {
  const userName = document.getElementById("userId").value.trim();
  if (!userName) {
    alert("Please enter your name first.");
    return;
  }

  fetch(window.baseUrl + "/app/bookings/loyalty.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `userId=${encodeURIComponent(userName)}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.error) {
        loyaltyMessage.textContent = data.error;
        userStays = 0;
        updateTotal();
        return;
      }

      if (data.loyalty) {
        loyaltyMessage.textContent =
          "Welcome back! You get 1g off your booking.";
        userStays = data.stays;
      } else {
        loyaltyMessage.textContent =
          "Welcome new guest! please proceed with the booking";
        userStays = 0;
      }

      updateTotal();
    })
    .catch(() => {
      loyaltyMessage.textContent = "Something went wrong. Try again.";
    });
});
