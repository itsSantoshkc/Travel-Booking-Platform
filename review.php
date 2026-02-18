<style>
  .reviews-section {
    max-width: 800px;
  }

  .reviews-section .section-label {
    font-family: 'Instrument Serif', serif;
    font-size: 1.6rem;
    font-weight: 400;
    color: #1a1a1a;
    letter-spacing: -0.02em;
    margin-bottom: 0.2rem;
  }

  .reviews-section .section-sub {
    font-size: 0.82rem;
    color: #999;
    font-weight: 300;
    margin-bottom: 1.5rem;
  }

  .reviews-section .review-input-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.25rem 1.25rem 1rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.06);
    margin-bottom: 1.5rem;
    transition: box-shadow 0.2s;
  }

  .reviews-section .review-input-card:focus-within {
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
  }

  .reviews-section .input-top {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
  }

  .reviews-section .rv-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #c9d6f0, #e8dff5);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    color: #6b7fb8;
    font-weight: 600;
    letter-spacing: 0.05em;
  }

  .reviews-section .review-form {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .reviews-section .rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 2px;
  }

  .reviews-section .rating-input input {
    display: none;
  }

  .reviews-section .rating-input label {
    font-size: 1.3rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.12s, transform 0.12s;
    line-height: 1;
  }

  .reviews-section .rating-input label:hover {
    transform: scale(1.15);
  }

  .reviews-section .rating-input input:checked~label,
  .reviews-section .rating-input label:hover,
  .reviews-section .rating-input label:hover~label {
    color: #f5a623;
  }

  /* Error states */
  .reviews-section .review-form textarea.rv-error {
    border-radius: 8px;
    border: 1.5px solid #e53e3e !important;
    padding: 0.4rem 0.5rem;
    background: #fff5f5;
    transition: border 0.2s, background 0.2s;
  }

  .reviews-section .rating-input.rv-error label {
    color: #e8b4b4;
  }

  .reviews-section .review-form textarea {
    width: 100%;
    border: 1.5px solid transparent;
    outline: none;
    resize: none;
    padding: 0;
    background: transparent;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem;
    color: #1a1a1a;
    line-height: 1.6;
    min-height: 44px;
    border-radius: 8px;
    transition: border 0.2s, background 0.2s, padding 0.2s;
  }

  .reviews-section .review-form textarea::placeholder {
    color: #bbb;
    font-weight: 300;
  }

  .reviews-section .form-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-top: 0.75rem;
    border-top: 1px solid #f0f0f0;
    margin-top: 0.2rem;
  }

  .reviews-section .submit-btn {
    background: #1a1a1a;
    color: #fff;
    border: none;
    padding: 0.48rem 1.2rem;
    border-radius: 100px;
    font-size: 0.82rem;
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    cursor: pointer;
    letter-spacing: 0.02em;
    transition: background 0.15s, transform 0.12s;
  }

  .reviews-section .submit-btn:hover {
    background: #333;
    transform: translateY(-1px);
  }

  .reviews-section .submit-btn:active {
    transform: translateY(0);
  }

  .reviews-section .reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: rgba(0, 0, 0, 0.06);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.06);
  }

  .reviews-section .review-item {
    display: flex;
    gap: 0.85rem;
    padding: 1rem 1.2rem;
    background: #fff;
    transition: background 0.15s;
    animation: rv-fadeIn 0.3s ease both;
  }

  .reviews-section .review-item:hover {
    background: #fafafa;
  }

  @keyframes rv-fadeIn {
    from {
      opacity: 0;
      transform: translateY(6px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .reviews-section .item-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.05em;
  }

  .reviews-section .review-content {
    flex: 1;
  }

  .reviews-section .review-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.25rem;
  }

  .reviews-section .review-meta {
    display: flex;
    align-items: center;
    gap: 0.45rem;
  }

  .reviews-section .review-name {
    font-weight: 600;
    font-size: 0.83rem;
    color: #1a1a1a;
  }

  .reviews-section .review-stars {
    font-size: 0.72rem;
    color: #f5a623;
    letter-spacing: 1px;
  }

  .reviews-section .review-time {
    font-size: 0.7rem;
    color: #ccc;
    font-weight: 300;
  }

  .reviews-section .review-text {
    font-size: 0.83rem;
    color: #555;
    line-height: 1.55;
    font-weight: 300;
  }

  .error-message {
    color: red;
  }
</style>



<section class="reviews-section">

  <h2 class="section-label">What people say</h2>

  <div class="review-input-card">
    <div class="input-top">
      <div class="rv-avatar">YOU</div>
      <form class="review-form" id="reviewForm" method="post" action="utilities/newReview.php" onsubmit="return handleReviews()">
        <input type="text" name="package_id" id="package_id" hidden/>
        <div class="rating-input" id="ratingInput">
          <input type="radio" name="rating" id="r5" value="5"><label for="r5">★</label>
          <input type="radio" name="rating" id="r4" value="4"><label for="r4">★</label>
          <input type="radio" name="rating" id="r3" value="3"><label for="r3">★</label>
          <input type="radio" name="rating" id="r2" value="2"><label for="r2">★</label>
          <input type="radio" name="rating" id="r1" value="1"><label for="r1">★</label>
        </div>
        <textarea id="reviewText" name="review" placeholder="What was your experience like?" rows="2" maxlength="280"></textarea>
        <p class="error-message"></p>
        <div class="form-footer">
          <button type="submit" class="submit-btn">Post review</button>
        </div>
      </form>
    </div>
  </div>

  <div id="reviewsContainer" class="reviews-list">
<?php
require_once('./conn.php');
require_once('./model/Review.php');
require_once('./utilities/renderStar.php');


$package_id = isset($_GET['package']) ? $_GET['package'] : '';
$reviewObj = new Review($conn);
$reviews = $reviewObj->getReviewByPackageID($package_id);
foreach($reviews as $review){
  // array(8) { ["review_id"]=> string(36) "9a22b11e-d182-4398-9354-2dde55def9cc" 
  // ["review"]=> string(2) "ds" 
  // ["user_id"]=> string(36) "7e1c4682-1e5a-4ebf-b839-6899993fcd6c" 
  // ["rating"]=> int(3) ["createdAt"]=> string(19) "2026-02-18 21:13:00" 
  // ["package_id"]=> string(7) "PKG-012" 
  // ["firstName"]=> string(7) "Santosh" ["lastName"]=> string(2) "Kc" }
echo "<div class='review-item'>
        <div class='item-avatar' style='background:#fde8d8;color:#c0622a'>" 
            . $review['firstName'][0] . "." . $review['lastName'][0] . 
        "</div>
        <div class='review-content'>
            <div class='review-header'>
                <div class='review-meta'>
                    <span class='review-name'>" . htmlspecialchars($review['firstName']. " ".$review['lastName'] ) . "</span>
                    <span class='review-stars'>" . renderStars(intval($review['rating'])) . "</span>
                </div>
            <span class='review-time'>" .htmlspecialchars($review['createdAt']) . "</span>
            </div>
            <p class='review-text'>". htmlspecialchars($review['review']) ."</p>
        </div>
    </div>";
    }

      ?>
  </div>

</section>

<script>
 

   

    function stars(n) {
      return '★'.repeat(n) + '☆'.repeat(5 - n);
    }
       
    const container = document.getElementById('reviewsContainer');




  function handleReviews() {
    const textarea = document.getElementById('reviewText');
    const ratingInput = document.getElementById('ratingInput');
    const packageId = document.getElementById('package_id');
    const errorMessage = document.querySelector('.error-message');
    textarea.addEventListener('input', () => textarea.classList.remove('rv-error'));
    ratingInput.addEventListener('change', () => ratingInput.classList.remove('rv-error'));
    const text = textarea.value.trim();
    const ratingEl = document.querySelector('input[name="rating"]:checked');
    const params = new URLSearchParams(window.location.search);
     packageId.value = params.get('package');

    let hasError = false;
    errorMessage.textContent = "";
    if (!ratingEl || !text) {
      ratingInput.classList.add('rv-error');
      textarea.classList.add('rv-error');
      errorMessage.textContent += "Error! Please Give a review and rating before pressing submit"
      hasError = true;
    }

    if (hasError) return false;

    return true;
    errorMessage.textContent = "";
    const el = buildReviewEl({
      name: 'You',
      rating: +ratingEl.value,
      text
    }, 0);
    el.style.animationDelay = '0s';
    container.insertBefore(el, container.firstChild);
    this.reset();

  }
</script>