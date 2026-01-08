const rooms = window.rooms;
const select = document.getElementById("room");

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
  document.getElementById("outImage").src = "/assets/images/" + room.outImage;
  document.getElementById("inImage").src = "/assets/images/" + room.inImage;
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

  totalEl.innerHTML = `<strong>Your total:</strong> ${total} g`;
}

select.addEventListener("change", updateTotal);
featureCheckboxes.forEach((cb) => cb.addEventListener("change", updateTotal));
checkInInput.addEventListener("change", updateTotal);
checkOutInput.addEventListener("change", updateTotal);
