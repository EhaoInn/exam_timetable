<div class="container py-5" style="max-width: 560px;">
  <div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">

      <div class="mb-4">
        <h5 class="fw-semibold mb-0">New Schedule</h5>
        <p class="text-muted small mb-0">Fill in the details to create a new schedule</p>
      </div>

      <form>

        <div class="mb-4">
          <label for="title" class="form-label fw-medium">
            Schedule title
            <i class="fa-solid fa-star-of-life text-danger ms-1" style="font-size: 8px; vertical-align: super;"></i>
          </label>
          <input
            type="text"
            class="form-control"
            id="title"
            placeholder="e.g. Biology Term 2">
        </div>

        <div class="mb-4">
          <label class="form-label fw-medium">
            Add members
          </label>
          <div class="border rounded-3 p-3 d-flex flex-column gap-2">
            <div class="form-check mb-0">
              <input type="checkbox" class="form-check-input" id="member1">
              <label class="form-check-label" for="member1">Ehao Inn</label>
            </div>
            <div class="form-check mb-0">
              <input type="checkbox" class="form-check-input" id="member2">
              <label class="form-check-label" for="member2">Makara Then</label>
            </div>
            <div class="form-check mb-0">
              <input type="checkbox" class="form-check-input" id="member3">
              <label class="form-check-label" for="member3">Dara Sok</label>
            </div>
          </div>
          <!-- <div class="form-text">Select one or more members to share your schedule.</div> -->
        </div>

        <hr class="my-4">

        <div class="d-flex align-items-center justify-content-end gap-2">
          <a href="./?page=dashboard" class="btn btn-outline-secondary px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
          </a>
          <button type="submit" class="btn btn-success px-4">
            <i class="fa-solid fa-plus me-2"></i>Add schedule
          </button>
        </div>

      </form>
    </div>
  </div>
</div>