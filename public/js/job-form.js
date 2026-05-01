document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-job-form]');
    if (!form) {
        return;
    }

    var skillsContainer = form.querySelector('[data-skills-container]');
    var skillTemplate = form.querySelector('[data-skill-template]');
    var addSkillButton = form.querySelector('[data-add-skill]');
    var maxSkills = Number(skillsContainer ? skillsContainer.dataset.maxSkills || 5 : 5);

    function getSkillRows() {
        return Array.prototype.slice.call(form.querySelectorAll('[data-skill-row]'));
    }

    function reindexSkillRows() {
        getSkillRows().forEach(function (row, index) {
            var skillSelect = row.querySelector('[data-skill-select]');
            var proficiencySelect = row.querySelector('select:not([data-skill-select])');

            if (skillSelect) {
                skillSelect.name = 'skills[' + index + '][skill_id]';
            }

            if (proficiencySelect) {
                proficiencySelect.name = 'skills[' + index + '][proficiency_level_id]';
            }
        });
    }

    function updateDuplicateState(changedSelect) {
        var values = getSkillRows()
            .map(function (row) {
                var select = row.querySelector('[data-skill-select]');
                return select ? select.value : '';
            })
            .filter(Boolean);

        var hasDuplicate = values.length !== new Set(values).size;
        if (hasDuplicate && changedSelect) {
            window.alert(skillsContainer.dataset.duplicateMessage || 'Duplicate skills are not allowed.');
            changedSelect.value = '';
        }
    }

    function addSkillRow() {
        if (!skillsContainer || !skillTemplate) {
            return;
        }

        if (getSkillRows().length >= maxSkills) {
            window.alert(skillsContainer.dataset.maxMessage || 'You can add up to 5 required skills.');
            return;
        }

        skillsContainer.appendChild(skillTemplate.content.cloneNode(true));
        reindexSkillRows();
    }

    if (addSkillButton) {
        addSkillButton.addEventListener('click', addSkillRow);
    }

    form.addEventListener('click', function (event) {
        if (!event.target.matches('[data-remove-skill]')) {
            return;
        }

        if (getSkillRows().length <= 1) {
            window.alert(skillsContainer.dataset.minMessage || 'Please keep at least 1 required skill.');
            return;
        }

        var row = event.target.closest('[data-skill-row]');
        if (row) {
            row.remove();
            reindexSkillRows();
        }
    });

    form.addEventListener('change', function (event) {
        if (event.target.matches('[data-skill-select]')) {
            updateDuplicateState(event.target);
        }
    });

    var countrySelect = form.querySelector('[data-country-select]');
    var citySelect = form.querySelector('[data-city-select]');
    var districtSelect = form.querySelector('[data-district-select]');

    function filterOptions(select, dependencyKey, dependencyValue) {
        if (!select) {
            return;
        }

        Array.prototype.slice.call(select.options).forEach(function (option, index) {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            var optionDependency = option.dataset[dependencyKey] || '';
            var visible = !dependencyValue || optionDependency === dependencyValue;
            option.hidden = !visible;

            if (!visible && option.selected) {
                select.value = '';
            }
        });
    }

    function syncLocationOptions() {
        var countryId = countrySelect ? countrySelect.value : '';
        var cityId = citySelect ? citySelect.value : '';
        filterOptions(citySelect, 'countryId', countryId);
        filterOptions(districtSelect, 'cityId', cityId);
    }

    if (countrySelect) {
        countrySelect.addEventListener('change', function () {
            if (citySelect) {
                citySelect.value = '';
            }
            if (districtSelect) {
                districtSelect.value = '';
            }
            syncLocationOptions();
        });
    }

    if (citySelect) {
        citySelect.addEventListener('change', function () {
            if (districtSelect) {
                districtSelect.value = '';
            }
            syncLocationOptions();
        });
    }

    reindexSkillRows();
    syncLocationOptions();
});
