@props(['count' => 4])

<div class="job-carousel-track" role="list" aria-label="Skeleton lowongan pekerjaan">
    @for ($i = 0; $i < $count; $i++)
        <article class="job-card-v2 job-card-skeleton" role="listitem" aria-hidden="true">
            <div class="skeleton-wrapper">
                <div class="skeleton-row">
                    <div class="skeleton skeleton-circle job-initial-skeleton"></div>
                    <div class="skeleton-text-wrapper">
                        <div class="skeleton skeleton-text skeleton-title"></div>
                        <div class="skeleton skeleton-text skeleton-subtitle"></div>
                    </div>
                    <div class="skeleton skeleton-circle skeleton-bookmark"></div>
                </div>
                
                <div class="skeleton-row skeleton-tags">
                    <div class="skeleton skeleton-pill"></div>
                    <div class="skeleton skeleton-pill"></div>
                </div>
                
                <div class="skeleton-row skeleton-meta">
                    <div class="skeleton skeleton-text skeleton-salary"></div>
                    <div class="skeleton skeleton-text skeleton-deadline"></div>
                </div>
                
                <div class="skeleton skeleton-btn"></div>
            </div>
        </article>
    @endfor
</div>

<style>
.job-card-skeleton {
    pointer-events: none;
}

.skeleton-wrapper {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.skeleton-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.skeleton-row.skeleton-tags {
    justify-content: flex-start;
    gap: 8px;
}

.skeleton-row.skeleton-meta {
    flex-direction: column;
    gap: 8px;
    align-items: flex-start;
}

.skeleton-text-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.skeleton {
    background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s ease-in-out infinite;
    border-radius: 4px;
}

.skeleton-circle {
    border-radius: 50%;
}

.skeleton-text {
    height: 16px;
    border-radius: 4px;
}

.skeleton-title {
    width: 60%;
    height: 20px;
}

.skeleton-subtitle {
    width: 40%;
    height: 14px;
}

.skeleton-pill {
    height: 24px;
    width: 80px;
    border-radius: 9999px;
}

.skeleton-salary {
    width: 50%;
    height: 18px;
}

.skeleton-deadline {
    width: 40%;
    height: 14px;
}

.skeleton-bookmark {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}

.skeleton-btn {
    height: 40px;
    width: 100%;
    border-radius: 9999px;
    margin-top: 8px;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

@media (prefers-reduced-motion: reduce) {
    .skeleton {
        animation: none;
        background: #e2e8f0;
    }
}
</style>