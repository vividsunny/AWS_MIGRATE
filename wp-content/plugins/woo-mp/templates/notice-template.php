<script type="text/template" id="notice-template">
    <div class="notice notice-{{ type }} {{ isDismissible ? 'is-dismissible' : '' }}">
        <div class="notice-main">
            <div class="notice-content">
                {% if (raw) { %}
                    {! message !}
                {% } else { %}
                    <p>{! message !}</p>
                {% } %}
                {% if (details) { %}
                    <div id="notice-details-{{ id }}" class="notice-details" hidden>{! details !}</div>
                {% } %}
            </div>
            {% if (details) { %}
                <div>
                    <p>
                        <button type="button" class="button-link collapse-arrow" data-toggle="collapse" aria-controls="notice-details-{{ id }}" aria-expanded="false">Details</button>
                    </p>
                </div>
            {% } %}
            {% if (isDismissible) { %}
                <div>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            {% } %}
        </div>
    </div>
</script>
