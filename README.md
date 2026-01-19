# Stardew Hotel

This is a small web application, for a fictional hotel, inspired by the indie game _Stardew Valley_. It allows users to book rooms, add features to their stay, and handle payments through a transfer code system. The design and theme are influenced by the cozy and charming world of Stardew Valley created by the indie game developer _ConcernedApe_.

## URL
https://benalmroth.se/yrgopelago/

## Features

- View available rooms and their details.
- Select check-in and check-out dates.
- Add optional features to your booking.
- Generate a transfer code and finalize your booking.
- Automatic calculation of total cost.
- Integration with a transfer code API for handling payments.
- Get a full booking receipt.

## Languages Used

- **PHP** – Backend logic, database handling, and API integration.
- **JavaScript** – Interactive frontend elements (room previews, calendar display, total cost calculation, transfer code service).
- **HTML & CSS** – Page structure and styling.
- **SQLite** – Database for users, rooms, bookings, and features.
- **GuzzleHTTP** – PHP library for making API requests.

## Usage

1. Navigate to the booking page.
2. Select your desired room, check-in/check-out dates, and additional features.
3. Generate a transfer code using your name and the amount.
4. Finalize the booking once the transfer code is validated.
5. View your receipt and booking summary.

## Creator

This project was created by **Benita Almroth** (BAlmroth)

## License

This project is for educational use and is inspired by **Stardew Valley**. All game-related content belongs to respective creators/owners.

## Code review / John Ahlenhed

index.php: 47 - Your current code works well, but it would be more efficient and clear to separate calendar logic. For example require backend calendar separate.

validateBooking.php: 12 -13 - Sanitize input to minimize SQL injection.

validateBooking.php: 46 - Statement only verifies username. Anyone can use other users discounts. Instead verify to centralbank with api key.

validateBooking.php: 71 - SQL injection risk. Should validate numeric values before prepare and execute.

validateBooking.php 96 - 138 - The transaction is made before validating with the database. This could result in deposit even if validation is failed.

validateBooking.php: 156 - API Key is exposed in receipt.

validateBooking.php: 141 - 168 - Receipt is sent to DB before received. If API call fails, the booking is already in DB.
